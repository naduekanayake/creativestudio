<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number', 'client_id', 'invoice_id',
        'amount', 'method', 'payment_date',
        'reference', 'notes', 'status',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Completed' => 'bg-green-500/20 text-green-400',
            'Pending'   => 'bg-orange-500/20 text-orange-400',
            'Failed'    => 'bg-red-500/20 text-red-400',
            'Refunded'  => 'bg-gray-500/20 text-gray-400',
            default     => 'bg-gray-500/20 text-gray-400',
        };
    }

    public function getMethodColorAttribute(): string
    {
        return match($this->method) {
            'Cash'          => 'bg-green-500/20 text-green-400',
            'Bank Transfer' => 'bg-blue-500/20 text-blue-400',
            'Cheque'        => 'bg-purple-500/20 text-purple-400',
            'Online'        => 'bg-orange-500/20 text-orange-400',
            'Card'          => 'bg-pink-500/20 text-pink-400',
            default         => 'bg-gray-500/20 text-gray-400',
        };
    }
}