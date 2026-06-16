<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'client_id',
        'type', 'remind_date', 'remind_time',
        'status', 'priority',
    ];

    protected $casts = [
        'remind_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'Pending' => 'bg-orange-500/20 text-orange-400',
            'Done'    => 'bg-green-500/20 text-green-400',
            'Snoozed' => 'bg-gray-500/20 text-gray-400',
        ];
        return $colors[$this->status] ?? 'bg-gray-500/20 text-gray-400';
    }

    public function getPriorityColorAttribute(): string
    {
        $colors = [
            'High'   => 'bg-red-500/20 text-red-400',
            'Medium' => 'bg-orange-500/20 text-orange-400',
            'Low'    => 'bg-gray-500/20 text-gray-400',
        ];
        return $colors[$this->priority] ?? 'bg-gray-500/20 text-gray-400';
    }

    public function getTypeColorAttribute(): string
    {
        $colors = [
            'Payment'   => 'bg-green-500/20 text-green-400',
            'Delivery'  => 'bg-blue-500/20 text-blue-400',
            'Shoot'     => 'bg-pink-500/20 text-pink-400',
            'Follow Up' => 'bg-purple-500/20 text-purple-400',
            'Other'     => 'bg-gray-500/20 text-gray-400',
        ];
        return $colors[$this->type] ?? 'bg-gray-500/20 text-gray-400';
    }

    public function getIsOverdueAttribute(): bool
    {
        return Carbon::parse($this->remind_date)->lt(Carbon::today())
            && $this->status === 'Pending';
    }
}