<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'description',
        'price', 'image', 'features',
        'total_bookings', 'status',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
    ];

    public function getFormattedPriceAttribute(): string
{
    return 'Rs. ' . number_format((float) $this->price);
}
    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'Photography' => 'bg-purple-500/20 text-purple-400',
            'Videography' => 'bg-blue-500/20 text-blue-400',
            'Combo'       => 'bg-orange-500/20 text-orange-400',
            default       => 'bg-gray-500/20 text-gray-400',
        };
    }
}