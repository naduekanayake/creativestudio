<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'company',
        'type', 'address', 'city', 'website',
        'notes', 'tags', 'status',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        return strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function deliverables()
    {
        return $this->hasMany(Deliverable::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
}