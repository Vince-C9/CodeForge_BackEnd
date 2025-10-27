<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Form Submission Model
 *
 * Represents all form submissions from the website including
 * contact forms and quote requests from the configurator.
 */
class FormSubmission extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'message',
        'contact_reason',
        'service',
        'configuration',
        'total_price',
        'ip_address',
        'user_agent',
        'recaptcha_token',
        'recaptcha_score',
        'status',
        'read_at',
        'responded_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'configuration' => 'array',
        'total_price' => 'decimal:2',
        'recaptcha_score' => 'decimal:1',
        'read_at' => 'datetime',
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Scope to filter by type
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by status
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get unread submissions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Mark submission as read
     *
     * @return bool
     */
    public function markAsRead(): bool
    {
        return $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    /**
     * Mark submission as responded
     *
     * @return bool
     */
    public function markAsResponded(): bool
    {
        return $this->update([
            'status' => 'responded',
            'responded_at' => now(),
        ]);
    }

    /**
     * Check if submission is a contact form
     *
     * @return bool
     */
    public function isContactForm(): bool
    {
        return $this->type === 'contact';
    }

    /**
     * Check if submission is a quote request
     *
     * @return bool
     */
    public function isQuoteRequest(): bool
    {
        return $this->type === 'quote';
    }

    /**
     * Get the submission's sanitized IP address for display
     *
     * @return string|null
     */
    public function getSanitizedIpAttribute(): ?string
    {
        if (!$this->ip_address) {
            return null;
        }

        // Anonymize the last octet for IPv4 or last group for IPv6
        if (filter_var($this->ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return preg_replace('/\d+$/', 'xxx', $this->ip_address);
        }

        return preg_replace('/[^:]+$/', 'xxxx', $this->ip_address);
    }
}
