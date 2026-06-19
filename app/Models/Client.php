<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'company',
        'type', 'lead_source', 'address', 'city', 'website',
        'notes', 'tags', 'status',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    // Lead source options — form සහ reports වලට පොදුවේ පාවිච්චි කරන්න
    public static array $leadSources = [
        'Facebook',
        'Instagram',
        'TikTok',
        'WhatsApp',
        'Google Search',
        'Website',
        'Referral',
        'Friend Recommendation',
        'Walk-In',
        'YouTube',
        'LinkedIn',
        'Other',
    ];

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        return strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    }

    public function getLeadSourceColorAttribute(): string
    {
        $colors = [
            'Facebook'              => 'bg-blue-500/20 text-blue-400',
            'Instagram'             => 'bg-pink-500/20 text-pink-400',
            'TikTok'                => 'bg-gray-500/20 text-gray-300',
            'WhatsApp'              => 'bg-green-500/20 text-green-400',
            'Google Search'         => 'bg-red-500/20 text-red-400',
            'Website'               => 'bg-purple-500/20 text-purple-400',
            'Referral'              => 'bg-orange-500/20 text-orange-400',
            'Friend Recommendation' => 'bg-yellow-500/20 text-yellow-400',
            'Walk-In'               => 'bg-teal-500/20 text-teal-400',
            'YouTube'               => 'bg-red-500/20 text-red-400',
            'LinkedIn'              => 'bg-blue-500/20 text-blue-400',
            'Other'                 => 'bg-gray-500/20 text-gray-400',
        ];
        return $colors[$this->lead_source] ?? 'bg-gray-500/20 text-gray-400';
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function deliverables()
    {
        return $this->hasMany(Deliverable::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
}