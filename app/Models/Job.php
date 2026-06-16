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

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'Inquiry'     => 'bg-gray-500/20 text-gray-400',
            'Confirmed'   => 'bg-blue-500/20 text-blue-400',
            'In Progress' => 'bg-yellow-500/20 text-yellow-400',
            'Editing'     => 'bg-purple-500/20 text-purple-400',
            'Delivered'   => 'bg-teal-500/20 text-teal-400',
            'Completed'   => 'bg-green-500/20 text-green-400',
            'Cancelled'   => 'bg-red-500/20 text-red-400',
        ];
        return $colors[$this->status] ?? 'bg-gray-500/20 text-gray-400';
    }

    public function getPriorityColorAttribute()
    {
        $colors = [
            'High'   => 'bg-red-500/20 text-red-400',
            'Medium' => 'bg-orange-500/20 text-orange-400',
            'Low'    => 'bg-gray-500/20 text-gray-400',
        ];
        return $colors[$this->priority] ?? 'bg-gray-500/20 text-gray-400';
    }

    public function getTypeColorAttribute()
    {
        $colors = [
            'Wedding'    => 'bg-pink-500/20 text-pink-400',
            'Portrait'   => 'bg-purple-500/20 text-purple-400',
            'Commercial' => 'bg-blue-500/20 text-blue-400',
            'Event'      => 'bg-orange-500/20 text-orange-400',
            'Product'    => 'bg-teal-500/20 text-teal-400',
            'Other'      => 'bg-gray-500/20 text-gray-400',
        ];
        return $colors[$this->type] ?? 'bg-gray-500/20 text-gray-400';
    }
}