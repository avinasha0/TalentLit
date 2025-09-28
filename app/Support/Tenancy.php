<?php

namespace App\Support;

use App\Models\Tenant;

final class Tenancy
{
    /** @var Tenant|null */
    private static $tenant;

    public static function set(?Tenant $tenant): void
    {
        self::$tenant = $tenant;
    }

    public static function get(): ?Tenant
    {
        return self::$tenant;
    }

    public static function id(): ?string
    {
        return self::$tenant?->id;
    }

    public static function forget(): void
    {
        self::$tenant = null;
    }
}
