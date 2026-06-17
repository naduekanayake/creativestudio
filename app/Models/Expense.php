<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'category', 'amount',
        'expense_date', 'payment_method', 'receipt_number',
        'notes', 'status',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'Approved' => 'bg-green-500/20 text-green-400',
            'Pending'  => 'bg-orange-500/20 text-orange-400',
            'Rejected' => 'bg-red-500/20 text-red-400',
        ];
        return $colors[$this->status] ?? 'bg-gray-500/20 text-gray-400';
    }

    public function getCategoryColorAttribute(): string
    {
        $colors = [
            'Equipment'   => 'bg-blue-500/20 text-blue-400',
            'Software'    => 'bg-purple-500/20 text-purple-400',
            'Transport'   => 'bg-yellow-500/20 text-yellow-400',
            'Food'        => 'bg-orange-500/20 text-orange-400',
            'Marketing'   => 'bg-pink-500/20 text-pink-400',
            'Studio Rent' => 'bg-teal-500/20 text-teal-400',
            'Utilities'   => 'bg-cyan-500/20 text-cyan-400',
            'Salary'      => 'bg-green-500/20 text-green-400',
            'Other'       => 'bg-gray-500/20 text-gray-400',
        ];
        return $colors[$this->category] ?? 'bg-gray-500/20 text-gray-400';
    }
}