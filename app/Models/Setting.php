<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getAll(): array
    {
        return static::pluck('value', 'key')->toArray();
    }

    
    public static function logoUrl(): ?string
    {
        $path = static::get('logo_path');
        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        return null;
    }
}