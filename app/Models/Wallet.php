<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Wallet Model
 * 
 * Manages user wallet balance and transactions
 * 
 * @property int $id
 * @property int $user_id
 * @property decimal $balance
 * @property decimal $locked_balance
 * @property string $currency
 * @property bool $is_active
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Wallet extends Model
{
    use HasFactory;

    /**
     * Default currency
     */
    const DEFAULT_CURRENCY = 'INR';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'balance',
        'locked_balance',
        'currency',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'locked_balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'balance' => 0.00,
        'locked_balance' => 0.00,
        'currency' => self::DEFAULT_CURRENCY,
        'is_active' => true,
    ];

    /**
     * Get the user that owns the wallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all transactions for this wallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get available balance (total - locked)
     *
     * @return float
     */
    public function getAvailableBalanceAttribute(): float
    {
        return (float) ($this->balance - $this->locked_balance);
    }

    /**
     * Get formatted balance with currency symbol
     *
     * @return string
     */
    public function getFormattedBalanceAttribute(): string
    {
        return '₹' . number_format($this->balance, 2);
    }

    /**
     * Get formatted available balance
     *
     * @return string
     */
    public function getFormattedAvailableBalanceAttribute(): string
    {
        return '₹' . number_format($this->available_balance, 2);
    }

    /**
     * Add funds to wallet
     *
     * @param float $amount
     * @param string $description
     * @param string $type
     * @return bool
     */
    public function credit(float $amount, string $description = 'Credit', string $type = 'credit'): bool
    {
        if ($amount <= 0) {
            return false;
        }

        $this->increment('balance', $amount);

        // Log transaction
        $this->transactions()->create([
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $this->balance - $amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'status' => 'completed',
        ]);

        return true;
    }

    /**
     * Deduct funds from wallet
     *
     * @param float $amount
     * @param string $description
     * @param string $type
     * @return bool
     */
    public function debit(float $amount, string $description = 'Debit', string $type = 'debit'): bool
    {
        if ($amount <= 0 || $this->available_balance < $amount) {
            return false;
        }

        $this->decrement('balance', $amount);

        // Log transaction
        $this->transactions()->create([
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $this->balance + $amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'status' => 'completed',
        ]);

        return true;
    }

    /**
     * Lock funds (for pending transactions)
     *
     * @param float $amount
     * @return bool
     */
    public function lock(float $amount): bool
    {
        if ($amount <= 0 || $this->available_balance < $amount) {
            return false;
        }

        $this->increment('locked_balance', $amount);
        return true;
    }

    /**
     * Unlock funds
     *
     * @param float $amount
     * @return bool
     */
    public function unlock(float $amount): bool
    {
        if ($amount <= 0 || $this->locked_balance < $amount) {
            return false;
        }

        $this->decrement('locked_balance', $amount);
        return true;
    }

    /**
     * Transfer funds to another wallet
     *
     * @param Wallet $toWallet
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function transfer(Wallet $toWallet, float $amount, string $description = 'Transfer'): bool
    {
        if ($this->debit($amount, $description . ' (sent)', 'transfer_out')) {
            return $toWallet->credit($amount, $description . ' (received)', 'transfer_in');
        }

        return false;
    }
}
