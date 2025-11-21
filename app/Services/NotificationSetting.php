<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class NotificationSetting
{
    const CACHE_KEY = 'notification_settings';

    /**
     * Get all notification settings.
     */
    public static function get()
    {
        return Cache::get(self::CACHE_KEY, [
            'low_stock_threshold' => 5,
            'expiry_days_warning' => 30,
            'email_enabled'       => false,
            'sms_enabled'         => false,
            'whatsapp_enabled'    => false,
        ]);
    }

    /**
     * Update notification settings.
     */
    public static function update(array $settings)
    {
        $current = self::get();

        $updated = array_merge($current, $settings);

        Cache::put(self::CACHE_KEY, $updated, now()->addYear());

        return $updated;
    }

    /**
     * Get a single setting.
     */
    public static function getValue($key)
    {
        $settings = self::get();
        return $settings[$key] ?? null;
    }
}
