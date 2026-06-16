<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deliverable extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'client_id', 'job_id', 'type',
        'quantity', 'delivery_method', 'due_date',
        'delivered_date', 'drive_link', 'notes', 'status',
    ];

    protected $casts = [
        'due_date'       => 'date',
        'delivered_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'Pending'     => 'bg-gray-500/20 text-gray-400',
            'In Progress' => 'bg-yellow-500/20 text-yellow-400',
            'Ready'       => 'bg-blue-500/20 text-blue-400',
            'Delivered'   => 'bg-teal-500/20 text-teal-400',
            'Approved'    => 'bg-green-500/20 text-green-400',
        ];
        return $colors[$this->status] ?? 'bg-gray-500/20 text-gray-400';
    }

    public function getTypeColorAttribute(): string
    {
        $colors = [
            'Photos'       => 'bg-pink-500/20 text-pink-400',
            'Videos'       => 'bg-purple-500/20 text-purple-400',
            'Album'        => 'bg-orange-500/20 text-orange-400',
            'Raw Files'    => 'bg-red-500/20 text-red-400',
            'Edited Files' => 'bg-blue-500/20 text-blue-400',
            'Prints'       => 'bg-teal-500/20 text-teal-400',
            'Other'        => 'bg-gray-500/20 text-gray-400',
        ];
        return $colors[$this->type] ?? 'bg-gray-500/20 text-gray-400';
    }
}