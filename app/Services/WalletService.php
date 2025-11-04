<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Exception;

class WalletService
{
    /**
     * Top up wallet balance
     */
    public function topup(User $user, float $amount, ?User $admin = null, ?string $description = null, ?array $meta = null): WalletTransaction
    {
        if ($amount <= 0) {
            throw new Exception('Jumlah top up harus lebih dari 0');
        }

        return DB::transaction(function () use ($user, $amount, $admin, $description, $meta) {
            $wallet = $user->getOrCreateWallet();

            if (!$wallet->is_active) {
                throw new Exception('Wallet tidak aktif');
            }

            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore + $amount;

            // Update wallet
            $wallet->update([
                'balance' => $balanceAfter,
                'total_topup' => $wallet->total_topup + $amount,
                'last_transaction_at' => now(),
            ]);

            // Create transaction record
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'type' => 'topup',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description ?? 'Top up saldo',
                'admin_id' => $admin?->id,
                'status' => 'completed',
                'meta' => $meta,
            ]);

            return $transaction;
        });
    }

    /**
     * Deduct balance for payment
     */
    public function deduct(User $user, float $amount, ?string $referenceType = null, ?int $referenceId = null, ?string $description = null, ?array $meta = null): WalletTransaction
    {
        if ($amount <= 0) {
            throw new Exception('Jumlah pembayaran harus lebih dari 0');
        }

        return DB::transaction(function () use ($user, $amount, $referenceType, $referenceId, $description, $meta) {
            $wallet = $user->getOrCreateWallet();

            if (!$wallet->is_active) {
                throw new Exception('Wallet tidak aktif');
            }

            if (!$wallet->hasSufficientBalance($amount)) {
                throw new Exception('Saldo tidak mencukupi. Saldo Anda: ' . $wallet->formatted_balance);
            }

            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore - $amount;

            // Update wallet
            $wallet->update([
                'balance' => $balanceAfter,
                'total_spent' => $wallet->total_spent + $amount,
                'last_transaction_at' => now(),
            ]);

            // Create transaction record
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'type' => 'payment',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'description' => $description ?? 'Pembayaran pesanan',
                'status' => 'completed',
                'meta' => $meta,
            ]);

            return $transaction;
        });
    }

    /**
     * Refund balance
     */
    public function refund(User $user, float $amount, ?string $referenceType = null, ?int $referenceId = null, ?string $description = null, ?array $meta = null): WalletTransaction
    {
        if ($amount <= 0) {
            throw new Exception('Jumlah refund harus lebih dari 0');
        }

        return DB::transaction(function () use ($user, $amount, $referenceType, $referenceId, $description, $meta) {
            $wallet = $user->getOrCreateWallet();

            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore + $amount;

            // Update wallet
            $wallet->update([
                'balance' => $balanceAfter,
                'total_spent' => max(0, $wallet->total_spent - $amount),
                'last_transaction_at' => now(),
            ]);

            // Create transaction record
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'type' => 'refund',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'description' => $description ?? 'Refund pembayaran',
                'status' => 'completed',
                'meta' => $meta,
            ]);

            return $transaction;
        });
    }

    /**
     * Get wallet balance
     */
    public function getBalance(User $user): float
    {
        $wallet = $user->wallet;
        return $wallet ? $wallet->balance : 0;
    }

    /**
     * Check if user has sufficient balance
     */
    public function hasSufficientBalance(User $user, float $amount): bool
    {
        $wallet = $user->wallet;
        return $wallet && $wallet->hasSufficientBalance($amount);
    }

    /**
     * Get transaction history
     */
    public function getTransactionHistory(User $user, ?string $type = null, int $perPage = 15)
    {
        $query = WalletTransaction::where('user_id', $user->id)
            ->with(['admin'])
            ->latest();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->paginate($perPage);
    }

    /**
     * Activate wallet
     */
    public function activateWallet(Wallet $wallet): bool
    {
        return $wallet->update(['is_active' => true]);
    }

    /**
     * Deactivate wallet
     */
    public function deactivateWallet(Wallet $wallet): bool
    {
        return $wallet->update(['is_active' => false]);
    }
}
