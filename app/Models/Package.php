<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'currency',
        'status',
        'duration',
        'duration_type',
        'description',
    ];

    public static function seedPackages()
    {
        $packages = [
            ['name' => 'Basic', 'price' => 100, 'duration' => 30],
            ['name' => 'Standard', 'price' => 200, 'duration' => 60],
            ['name' => 'Premium', 'price' => 300, 'duration' => 90],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
