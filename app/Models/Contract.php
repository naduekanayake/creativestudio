<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_number', 'client_id', 'job_id', 'title', 'type',
        'event_date', 'event_location', 'total_amount', 'advance_amount',
        'contract_date', 'terms', 'notes', 'status',
    ];

    protected $casts = [
        'event_date'    => 'date',
        'contract_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function getBalanceAttribute(): float
    {
        return $this->total_amount - $this->advance_amount;
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'Draft'     => 'bg-gray-500/20 text-gray-400',
            'Sent'      => 'bg-blue-500/20 text-blue-400',
            'Signed'    => 'bg-green-500/20 text-green-400',
            'Completed' => 'bg-teal-500/20 text-teal-400',
            'Cancelled' => 'bg-red-500/20 text-red-400',
        ];
        return $colors[$this->status] ?? 'bg-gray-500/20 text-gray-400';
    }
}