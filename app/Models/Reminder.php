<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_type', 'source_id',
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

    public function getWhatsappMessageAttribute(): string
    {
        $studio = \App\Models\Setting::get('studio_name', 'Creative Studio');
        $clientName = $this->client->name ?? 'there';
        $date = Carbon::parse($this->remind_date)->format('d M Y');

        return match($this->type) {
            'Payment'   => "Hi {$clientName}, this is a friendly reminder from {$studio} regarding your payment due on {$date}. {$this->title}. Please let us know if you have any questions. Thank you!",
            'Delivery'  => "Hi {$clientName}, good news from {$studio}! Your deliverables are ready. {$this->title}. We'll be in touch shortly.",
            'Shoot'     => "Hi {$clientName}, reminder from {$studio}: your photoshoot is scheduled for {$date}. {$this->title}. Looking forward to it!",
            'Follow Up' => "Hi {$clientName}, just following up from {$studio}. {$this->title}. Feel free to reach out anytime!",
            default     => "Hi {$clientName}, a reminder from {$studio}: {$this->title}.",
        };
    }

    public function getWhatsappLinkAttribute(): ?string
    {
        if (!$this->client || !$this->client->phone) {
            return null;
        }
        $phone = preg_replace('/[^0-9]/', '', $this->client->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '94' . substr($phone, 1);
        }
        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($this->whatsapp_message);
    }
}