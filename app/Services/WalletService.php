<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WalletTopupRequest;
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

    /**
     * Create topup request
     */
    public function createTopupRequest(User $user, float $amount, ?string $userNotes = null): WalletTopupRequest
    {
        if ($amount <= 0) {
            throw new Exception('Jumlah top up harus lebih dari 0');
        }

        if ($amount < 1000) {
            throw new Exception('Jumlah minimal top up adalah Rp 1.000');
        }

        if ($amount > 100000000) {
            throw new Exception('Jumlah maksimal top up adalah Rp 100.000.000');
        }

        // Check if user has pending request
        $pendingRequest = WalletTopupRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingRequest) {
            throw new Exception('Anda masih memiliki request top up yang belum diproses. Silakan tunggu admin memproses request sebelumnya.');
        }

        return WalletTopupRequest::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => 'pending',
            'user_notes' => $userNotes,
        ]);
    }

    /**
     * Approve topup request
     */
    public function approveTopupRequest(WalletTopupRequest $request, User $admin, ?string $adminNotes = null): WalletTransaction
    {
        if (!$request->canBeProcessed()) {
            throw new Exception('Request sudah diproses sebelumnya');
        }

        return DB::transaction(function () use ($request, $admin, $adminNotes) {
            // Process topup
            $transaction = $this->topup(
                $request->user,
                $request->amount,
                $admin,
                'Top up saldo - Request #' . $request->id . ($request->user_notes ? ' - ' . $request->user_notes : ''),
                ['topup_method' => 'online_request', 'request_id' => $request->id]
            );

            // Update request status
            $request->update([
                'status' => 'approved',
                'admin_id' => $admin->id,
                'admin_notes' => $adminNotes,
                'wallet_transaction_id' => $transaction->id,
                'approved_at' => now(),
            ]);

            return $transaction;
        });
    }

    /**
     * Reject topup request
     */
    public function rejectTopupRequest(WalletTopupRequest $request, User $admin, ?string $adminNotes = null): WalletTopupRequest
    {
        if (!$request->canBeProcessed()) {
            throw new Exception('Request sudah diproses sebelumnya');
        }

        $request->update([
            'status' => 'rejected',
            'admin_id' => $admin->id,
            'admin_notes' => $adminNotes,
            'rejected_at' => now(),
        ]);

        return $request;
    }

    /**
     * Get pending topup requests
     */
    public function getPendingTopupRequests()
    {
        return WalletTopupRequest::with(['user', 'user.wallet'])
            ->pending()
            ->latest()
            ->get();
    }

    /**
     * Get all topup requests
     */
    public function getAllTopupRequests(?string $status = null, int $perPage = 20)
    {
        $query = WalletTopupRequest::with(['user', 'admin'])
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get user's topup requests
     */
    public function getUserTopupRequests(User $user, ?string $status = null, int $perPage = 15)
    {
        $query = $user->walletTopupRequests()
            ->with('admin')
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }
}
