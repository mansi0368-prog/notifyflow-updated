<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscriber extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'contact_list_id',
        'email',
        'first_name',
        'last_name',
        'status',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected $casts = [
        'subscribed_at'   => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contactList()
    {
        return $this->belongsTo(ContactList::class);
    }

    public function sends()
    {
        return $this->hasMany(CampaignSend::class);
    }

    // ── Scopes ────────────────────────────────────────

    public function scopeSubscribed($query)
    {
        return $query->where('status', 'subscribed');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ── Helpers ───────────────────────────────────────

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}") ?: $this->email;
    }
}