<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'tenant_name',
        'package_id',
        'date_activated',
        'date_suspended',
        'status'
    ];
}
