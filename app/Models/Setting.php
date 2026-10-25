<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public const CACHE_KEY = 'app_settings_dictionary';

    // Pastikan kedua kolom ini ada di fillable
    protected $fillable = ['key', 'value'];

    protected static function booted(): void
    {
        static::saved(fn () => self::purgeCache());
        static::deleted(fn () => self::purgeCache());
    }

    /**
     * Ambil seluruh konfigurasi sebagai key-value Collection yang di-cache permanen di memory/Redis.
     */
    public static function allCached(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return self::query()->pluck('value', 'key');
        });
    }

    /**
     * Helper untuk mengambil nilai setting dengan mudah via in-memory cache.
     */
    public static function value($key, $default = null)
    {
        return self::allCached()->get($key, $default);
    }

    /**
     * Invalidate cache setting saat ada perubahan atau penghapusan data.
     */
    public static function purgeCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget('settings:kop_signature_meta');
        Cache::forget('landing_page_stats_v3');
    }
}
