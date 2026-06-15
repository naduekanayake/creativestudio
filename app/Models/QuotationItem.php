<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id', 'item_name', 'description',
        'qty', 'unit_price', 'total',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}