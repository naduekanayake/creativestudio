<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_number', 'title', 'client_id', 'quotation_id',
        'type', 'event_date', 'event_location', 'description',
        'status', 'priority', 'budget', 'delivery_date', 'notes',
    ];

    protected $casts = [
        'event_date'    => 'date',
        'delivery_date' => 'date',
    ];

    // Project Type categories — grouped for the dropdown (optgroups)
    public static array $typeGroups = [
        'Wedding' => [
            'Wedding Photography',
            'Wedding Videography',
            'Wedding Photo + Video',
            'Homecoming',
            'Engagement',
            'Registration Ceremony',
            'Poruwa Ceremony',
            'Wedding Reception',
        ],
        'Pre-Wedding' => [
            'Pre Shoot',
            'Save The Date',
            'Engagement Shoot',
            'Couple Shoot',
            'Proposal Shoot',
        ],
        'Portrait' => [
            'Portrait Session',
            'Family Portrait',
            'Maternity Shoot',
            'Baby Shoot',
            'Birthday Shoot',
            'Graduation Shoot',
            'Fashion Shoot',
            'Personal Branding Shoot',
        ],
        'Event' => [
            'Birthday Party',
            'Anniversary',
            'Musical Show',
            'Religious Event',
            'School Event',
            'Sports Event',
            'Concert',
            'Award Ceremony',
            'Conference',
            'Seminar',
            'Exhibition',
        ],
        'Corporate' => [
            'Corporate Photography',
            'Corporate Videography',
            'Corporate Event Coverage',
            'Product Photography',
            'Product Videography',
            'Company Profile Video',
            'Interview Shoot',
            'Training Video',
            'Commercial Advertisement',
        ],
        'Social Media' => [
            'Social Media Content',
            'Reels Creation',
            'TikTok Content',
            'YouTube Production',
            'Podcast Production',
            'Influencer Content',
        ],
        'Commercial' => [
            'Real Estate Photography',
            'Real Estate Video',
            'Hotel Photography',
            'Restaurant Photography',
            'Food Photography',
            'Drone Photography',
            'Drone Videography',
            'Industrial Photography',
        ],
        'Other' => [
            'Other',
        ],
    ];

    // Flat list of all valid types (for validation)
    public static function allTypes(): array
    {
        return array_merge(...array_values(self::$typeGroups));
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Confirmed'   => 'bg-blue-500/20 text-blue-400',
            'In Progress' => 'bg-yellow-500/20 text-yellow-400',
            'Editing'     => 'bg-purple-500/20 text-purple-400',
            'Delivered'   => 'bg-teal-500/20 text-teal-400',
            'Completed'   => 'bg-green-500/20 text-green-400',
            'Cancelled'   => 'bg-red-500/20 text-red-400',
            default       => 'bg-gray-500/20 text-gray-400',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'High'   => 'bg-red-500/20 text-red-400',
            'Medium' => 'bg-orange-500/20 text-orange-400',
            default  => 'bg-gray-500/20 text-gray-400',
        };
    }

    // Category prefix එක (e.g. "Wedding Photography" → "Wedding") color එකට පාවිච්චි කරනවා
    public function getTypeColorAttribute(): string
    {
        $type = $this->type ?? '';

        $map = [
            'Wedding'      => 'bg-pink-500/20 text-pink-400',
            'Pre'          => 'bg-rose-500/20 text-rose-400',
            'Save'         => 'bg-rose-500/20 text-rose-400',
            'Engagement'   => 'bg-rose-500/20 text-rose-400',
            'Couple'       => 'bg-rose-500/20 text-rose-400',
            'Proposal'     => 'bg-rose-500/20 text-rose-400',
            'Portrait'     => 'bg-purple-500/20 text-purple-400',
            'Family'       => 'bg-purple-500/20 text-purple-400',
            'Maternity'    => 'bg-purple-500/20 text-purple-400',
            'Baby'         => 'bg-purple-500/20 text-purple-400',
            'Birthday'     => 'bg-orange-500/20 text-orange-400',
            'Graduation'   => 'bg-purple-500/20 text-purple-400',
            'Fashion'      => 'bg-purple-500/20 text-purple-400',
            'Personal'     => 'bg-purple-500/20 text-purple-400',
            'Anniversary'  => 'bg-orange-500/20 text-orange-400',
            'Musical'      => 'bg-orange-500/20 text-orange-400',
            'Religious'    => 'bg-orange-500/20 text-orange-400',
            'School'       => 'bg-orange-500/20 text-orange-400',
            'Sports'       => 'bg-orange-500/20 text-orange-400',
            'Concert'      => 'bg-orange-500/20 text-orange-400',
            'Award'        => 'bg-orange-500/20 text-orange-400',
            'Conference'   => 'bg-orange-500/20 text-orange-400',
            'Seminar'      => 'bg-orange-500/20 text-orange-400',
            'Exhibition'   => 'bg-orange-500/20 text-orange-400',
            'Corporate'    => 'bg-blue-500/20 text-blue-400',
            'Product'      => 'bg-teal-500/20 text-teal-400',
            'Company'      => 'bg-blue-500/20 text-blue-400',
            'Interview'    => 'bg-blue-500/20 text-blue-400',
            'Training'     => 'bg-blue-500/20 text-blue-400',
            'Commercial'   => 'bg-blue-500/20 text-blue-400',
            'Social'       => 'bg-cyan-500/20 text-cyan-400',
            'Reels'        => 'bg-cyan-500/20 text-cyan-400',
            'TikTok'       => 'bg-cyan-500/20 text-cyan-400',
            'YouTube'      => 'bg-red-500/20 text-red-400',
            'Podcast'      => 'bg-cyan-500/20 text-cyan-400',
            'Influencer'   => 'bg-cyan-500/20 text-cyan-400',
            'Real'         => 'bg-emerald-500/20 text-emerald-400',
            'Hotel'        => 'bg-emerald-500/20 text-emerald-400',
            'Restaurant'   => 'bg-emerald-500/20 text-emerald-400',
            'Food'         => 'bg-emerald-500/20 text-emerald-400',
            'Drone'        => 'bg-emerald-500/20 text-emerald-400',
            'Industrial'   => 'bg-emerald-500/20 text-emerald-400',
        ];

        $firstWord = explode(' ', $type)[0];
        return $map[$firstWord] ?? 'bg-gray-500/20 text-gray-400';
    }
}