<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Pastikan kedua kolom ini ada di fillable
    protected $fillable = ['key', 'value'];

    // Helper untuk mengambil nilai setting dengan mudah
    public static function value($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}