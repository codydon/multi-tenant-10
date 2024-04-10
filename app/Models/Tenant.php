<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;


    public static function createTenants($url, $name)
    {
        //GET TENANT_POSTFIXfrom .env
        $tenantPostfix = env('TENANT_POSTFIX');
        //join url and tenantPostfix with a dot
        $domain = $url . '.' . $tenantPostfix;
        $tenant = self::create(['id' => $url]);
        // $tenant = self::create(['id' => $url, 'name' => $name]);
        $tenant->domains()->create(['domain' => $domain]);
        // $tenant->domains()->create(['domain' => 'foo.localhost']);

        return $tenant;
    }
}