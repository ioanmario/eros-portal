<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // 👈 add this

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // 👈 add HasRoles here

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'affiliate_code',
        'referral_code',
        'referred_by_code',
        'referred_by_user_id',
        'plan',
        'stripe_customer_id',
        'stripe_subscription_id',
        'subscription_ends_at',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'subscription_ends_at' => 'datetime',
    ];

    // Affiliate relationships
    public function affiliate()
    {
        return $this->hasOne(Affiliate::class);
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by_user_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by_user_id');
    }

    public function brokerAccounts()
    {
        return $this->hasMany(BrokerAccount::class);
    }

    public function payoutRequests()
    {
        return $this->hasMany(PayoutRequest::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    // Helper methods
    public function isAffiliate(): bool
    {
        return $this->affiliate && $this->affiliate->isApproved();
    }

    public function generateReferralCode(): string
    {
        if (!$this->referral_code) {
            $this->referral_code = strtoupper(substr($this->name, 0, 3) . rand(1000, 9999));
            $this->save();
        }
        return $this->referral_code;
    }
}
