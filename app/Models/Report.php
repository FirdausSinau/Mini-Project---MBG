<?php

namespace App\Models;

use App\Enums\ReferralParty;
use App\Enums\ReportStatus;
use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'distribution_id',
    'incident_at',
    'description',
    'location_detail',
    'evidence_path',
    'evidence_original_name',
    'status',
    'review_note',
    'reviewed_by',
    'reviewed_at',
    'referral_party_type',
    'referral_party_name',
    'referral_note',
    'referred_by',
    'referred_at',
    'closure_note',
    'closed_by',
    'closed_at',
])]
class Report extends Model
{
    /** @use HasFactory<ReportFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'incident_at' => 'datetime',
            'status' => ReportStatus::class,
            'reviewed_at' => 'datetime',
            'referral_party_type' => ReferralParty::class,
            'referred_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(Distribution::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function closer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ReportStatusLog::class)->oldest('id');
    }
}
