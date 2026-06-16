<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'type', 'client_id', 'quotation_id', 'project_event',
        'issue_date', 'due_date', 'sub_total',
        'discount_percent', 'vat_percent', 'total_amount',
        'paid_amount', 'payment_status',
        'terms', 'payment_terms', 'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date'   => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Paid'      => 'bg-green-500/20 text-green-400',
            'Sent'      => 'bg-blue-500/20 text-blue-400',
            'Overdue'   => 'bg-red-500/20 text-red-400',
            'Cancelled' => 'bg-gray-500/20 text-gray-400',
            default     => 'bg-gray-500/20 text-gray-400',
        };
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return match($this->payment_status) {
            'Paid'    => 'bg-green-500/20 text-green-400',
            'Partial' => 'bg-orange-500/20 text-orange-400',
            default   => 'bg-red-500/20 text-red-400',
        };
    }

    public function getBalanceDueAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'Advance' => 'bg-purple-500/20 text-purple-400',
            'Final'   => 'bg-blue-500/20 text-blue-400',
            default   => 'bg-orange-500/20 text-orange-400',
        };
    }
}