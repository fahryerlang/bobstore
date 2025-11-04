<?php

namespace App\Services;

use App\Models\User;
use App\Models\PointTransaction;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class LoyaltyPointService
{
    /**
     * Point conversion rate: Rp per 1 point
     * 1000 Rp = 1 point (configurable)
     */
    const POINTS_PER_RUPIAH = 0.001; // 1 point per 1000 rupiah
    
    /**
     * Redemption rate: Rupiah per 1 point
     * 1 point = 100 Rp discount
     */
    const RUPIAH_PER_POINT = 100;
    
    /**
     * Member level thresholds (total points accumulated)
     */
    const MEMBER_LEVELS = [
        'bronze' => 0,      // 0 - 499 points
        'silver' => 500,    // 500 - 1999 points
        'gold' => 2000,     // 2000 - 4999 points
        'platinum' => 5000, // 5000+ points
    ];
    
    /**
     * Level multipliers for earning points
     */
    const LEVEL_MULTIPLIERS = [
        'bronze' => 1.0,   // 100% points
        'silver' => 1.2,   // 120% points
        'gold' => 1.5,     // 150% points
        'platinum' => 2.0, // 200% points
    ];

    /**
     * Calculate points earned from purchase amount
     */
    public function calculatePointsEarned(float $amount, User $user): int
    {
        $basePoints = floor($amount * self::POINTS_PER_RUPIAH);
        $multiplier = self::LEVEL_MULTIPLIERS[$user->member_level] ?? 1.0;
        
        return (int) floor($basePoints * $multiplier);
    }

    /**
     * Calculate rupiah value of points for redemption
     */
    public function calculatePointsValue(int $points): float
    {
        return $points * self::RUPIAH_PER_POINT;
    }

    /**
     * Award points to member after purchase
     */
    public function awardPoints(User $user, Sale $sale, float $amount): ?PointTransaction
    {
        // Only members (customers with role 'customer') can earn points
        if ($user->role !== 'customer') {
            return null;
        }

        $pointsEarned = $this->calculatePointsEarned($amount, $user);
        
        if ($pointsEarned <= 0) {
            return null;
        }

        return DB::transaction(function () use ($user, $sale, $pointsEarned) {
            // Update user points
            $user->increment('points', $pointsEarned);
            $user->refresh();

            // Check and update member level
            $this->updateMemberLevel($user);

            // Create point transaction record
            return PointTransaction::create([
                'user_id' => $user->id,
                'sale_id' => $sale->id,
                'type' => 'earn',
                'points' => $pointsEarned,
                'balance_after' => $user->points,
                'description' => "Earned {$pointsEarned} points from purchase (Invoice: {$sale->invoice_number})",
                'expires_at' => now()->addYear(), // Points expire after 1 year
            ]);
        });
    }

    /**
     * Redeem points for discount
     */
    public function redeemPoints(User $user, int $pointsToRedeem): array
    {
        // Check if user has enough points
        if ($user->points < $pointsToRedeem) {
            return [
                'success' => false,
                'message' => 'Poin tidak mencukupi',
                'available_points' => $user->points,
            ];
        }

        // Minimum redemption (e.g., 10 points minimum)
        if ($pointsToRedeem < 10) {
            return [
                'success' => false,
                'message' => 'Minimal penukaran poin adalah 10 poin',
            ];
        }

        $discountAmount = $this->calculatePointsValue($pointsToRedeem);

        return DB::transaction(function () use ($user, $pointsToRedeem, $discountAmount) {
            // Deduct points
            $user->decrement('points', $pointsToRedeem);
            $user->refresh();

            // Create point transaction record
            $transaction = PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'redeem',
                'points' => -$pointsToRedeem, // Negative for redemption
                'balance_after' => $user->points,
                'description' => "Redeemed {$pointsToRedeem} points for Rp " . number_format($discountAmount, 0, ',', '.') . " discount",
            ]);

            // Check and update member level
            $this->updateMemberLevel($user);

            return [
                'success' => true,
                'message' => 'Poin berhasil ditukar',
                'points_redeemed' => $pointsToRedeem,
                'discount_amount' => $discountAmount,
                'remaining_points' => $user->points,
                'transaction' => $transaction,
            ];
        });
    }

    /**
     * Update member level based on total accumulated points
     */
    public function updateMemberLevel(User $user): void
    {
        // Calculate total points ever earned
        $totalEarned = PointTransaction::where('user_id', $user->id)
            ->where('type', 'earn')
            ->sum('points');

        $newLevel = 'bronze';
        
        if ($totalEarned >= self::MEMBER_LEVELS['platinum']) {
            $newLevel = 'platinum';
        } elseif ($totalEarned >= self::MEMBER_LEVELS['gold']) {
            $newLevel = 'gold';
        } elseif ($totalEarned >= self::MEMBER_LEVELS['silver']) {
            $newLevel = 'silver';
        }

        if ($user->member_level !== $newLevel) {
            $user->update(['member_level' => $newLevel]);
        }
    }

    /**
     * Get member level info
     */
    public function getMemberLevelInfo(User $user): array
    {
        $totalEarned = PointTransaction::where('user_id', $user->id)
            ->where('type', 'earn')
            ->sum('points');

        $currentLevel = $user->member_level;
        $currentMultiplier = self::LEVEL_MULTIPLIERS[$currentLevel];

        // Calculate next level
        $nextLevel = null;
        $pointsToNextLevel = 0;

        $levels = array_keys(self::MEMBER_LEVELS);
        $currentLevelIndex = array_search($currentLevel, $levels);
        
        if ($currentLevelIndex < count($levels) - 1) {
            $nextLevel = $levels[$currentLevelIndex + 1];
            $pointsToNextLevel = self::MEMBER_LEVELS[$nextLevel] - $totalEarned;
        }

        return [
            'current_level' => $currentLevel,
            'current_multiplier' => $currentMultiplier,
            'current_points' => $user->points,
            'total_earned' => $totalEarned,
            'next_level' => $nextLevel,
            'points_to_next_level' => max(0, $pointsToNextLevel),
        ];
    }

    /**
     * Get point transaction history
     */
    public function getPointHistory(User $user, int $limit = 10)
    {
        return PointTransaction::where('user_id', $user->id)
            ->with('sale')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
