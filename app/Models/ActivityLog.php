<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'user_name', 'action', 'model_type',
        'model_id', 'model_name', 'description', 'icon', 'color',
    ];

    public static function log(string $action, string $modelType, $modelId, string $modelName, string $description, string $icon = 'activity', string $color = 'blue'): void
    {
        static::create([
            'user_id'     => Auth::id(),
            'user_name'   => Auth::check() ? Auth::user()->name : 'System',
            'action'      => $action,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'model_name'  => $modelName,
            'description' => $description,
            'icon'        => $icon,
            'color'       => $color,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getColorClassAttribute(): string
    {
        $colors = [
            'green'  => 'bg-green-500/20 text-green-400',
            'blue'   => 'bg-blue-500/20 text-blue-400',
            'red'    => 'bg-red-500/20 text-red-400',
            'orange' => 'bg-orange-500/20 text-orange-400',
            'purple' => 'bg-purple-500/20 text-purple-400',
            'pink'   => 'bg-pink-500/20 text-pink-400',
            'teal'   => 'bg-teal-500/20 text-teal-400',
            'gray'   => 'bg-gray-500/20 text-gray-400',
        ];
        return $colors[$this->color] ?? 'bg-blue-500/20 text-blue-400';
    }
}