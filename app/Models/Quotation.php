<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_number', 'client_id', 'project_event',
        'issue_date', 'valid_until', 'sub_total',
        'discount_percent', 'vat_percent', 'total_amount',
        'terms', 'payment_terms', 'status', 'share_token',
    ];

    protected $casts = [
        'issue_date'  => 'date',
        'valid_until' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($quotation) {
            if (empty($quotation->share_token)) {
                $quotation->share_token = Str::random(40);
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function getShareUrlAttribute(): string
    {
        if (empty($this->share_token)) {
            $this->share_token = Str::random(40);
            $this->save();
        }
        return route('quotations.public', $this->share_token);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Accepted' => 'bg-green-500/20 text-green-400',
            'Sent'     => 'bg-blue-500/20 text-blue-400',
            'Rejected' => 'bg-red-500/20 text-red-400',
            'Expired'  => 'bg-orange-500/20 text-orange-400',
            default    => 'bg-gray-500/20 text-gray-400',
        };
    }
}