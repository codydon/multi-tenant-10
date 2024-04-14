<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;


    public static function getTenants()
    {

        // dd(Tenant::all()); //note: tenancy_db_name can be seen
        return self::join('tenant_details', 'tenants.id', '=', 'tenant_details.tenant_id')
            ->join('packages', 'tenant_details.package_id', '=', 'packages.id')
            ->select('tenants.id', 'packages.id as package_id ', 'packages.name as package_name', 'tenant_name', 'package_id', 'date_activated', 'date_suspended', 'tenant_details.status')
            ->orderBy('tenants.id', 'asc')
            ->get();
    }

     public static function getTenant($id){
        return self::join('tenant_details', 'tenants.id', '=', 'tenant_details.tenant_id')
            ->join('packages', 'tenant_details.package_id', '=', 'packages.id')
            ->select('tenants.id', 'packages.id as package_id ', 'packages.name as package_name', 'tenant_name', 'package_id', 'date_activated', 'date_suspended', 'tenant_details.status')
            ->where('tenants.id', $id)
            ->first();
     }


    public static function createTenants($url, $name, $package_id)
    {
        //using package_id to get the package  details from the packages table
        $package = Package::find($package_id);

        //today timestamp
        $today = now();
        //add the duration to the current date
        $date_suspended = $today->addDays($package->duration);

        //GET TENANT_POSTFIXfrom .env
        $tenantPostfix = env('TENANT_POSTFIX');
        //join url and tenantPostfix with a dot
        $domain = $url . '.' . $tenantPostfix;

        // dd($url, $name, $package_id, $today, $date_suspended, $domain);
        NOTE: //only id and timestamp fields are required (others will be place in {data} column as json: tenancy principle)
        $tenant = self::create(['id' => $url]);
        // dd($tenant);
        //attach the tenant to the package and details
        TenantDetail::create([
            'tenant_id' => $tenant->id,
            'tenant_name' => $name,
            'package_id' => $package_id,
            'date_activated' => $today,
            'date_suspended' => $date_suspended,
            'status' => true,
        ]);

        // sync the tenant domain
        $tenant->domains()->create(['domain' => $domain]);
        // $tenant->domains()->create(['domain' => 'foo.localhost']);

        return $tenant;
    }
}
