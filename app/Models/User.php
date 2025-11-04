<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'points',
        'member_level',
        'member_since',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'member_since' => 'datetime',
        ];
    }

    /**
     * Get the sales for the user (customer).
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'user_id');
    }

    /**
     * Get the wallet for the user.
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Get wallet transactions for the user.
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get or create wallet for user
     */
    public function getOrCreateWallet()
    {
        return $this->wallet()->firstOrCreate([
            'user_id' => $this->id
        ], [
            'balance' => 0,
            'total_topup' => 0,
            'total_spent' => 0,
            'is_active' => true,
        ]);
    }

    /**
     * Get point transactions for the user.
     */
    public function pointTransactions()
    {
        return $this->hasMany(\App\Models\PointTransaction::class);
    }

    /**
     * Check if user is a member (customer)
     */
    public function isMember(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Get member level badge color
     */
    public function getMemberLevelColorAttribute(): string
    {
        return match($this->member_level) {
            'platinum' => 'text-purple-600 bg-purple-100',
            'gold' => 'text-yellow-600 bg-yellow-100',
            'silver' => 'text-gray-600 bg-gray-100',
            default => 'text-orange-600 bg-orange-100',
        };
    }

    /**
     * Get member level icon
     */
    public function getMemberLevelIconAttribute(): string
    {
        return match($this->member_level) {
            'platinum' => '💎',
            'gold' => '⭐',
            'silver' => '🥈',
            default => '🥉',
        };
    }
}