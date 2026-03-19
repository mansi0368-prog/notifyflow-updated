<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'subject',
        'body',
        'status',
        'scheduled_at',
        'total_recipients',
        'sent_count',
        'failed_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contactLists()
    {
        return $this->belongsToMany(ContactList::class, 'campaign_contact_list');
    }

    public function sends()
    {
        return $this->hasMany(CampaignSend::class);
    }

    // ── Scopes ────────────────────────────────────────

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ── Helpers ───────────────────────────────────────

    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'scheduled']);
    }

    public function progressPercentage(): int
    {
        if ($this->total_recipients === 0) return 0;
        return (int) round(($this->sent_count / $this->total_recipients) * 100);
    }
}