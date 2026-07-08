<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'items_per_week',
        'duration',
        'status',
        'price',
    ];

    protected $casts = [
        'status' => 'boolean',
        'price' => 'float',
        'items_per_week' => 'integer',
        'duration' => 'integer',
    ];

    public function customerPackages()
    {
        return $this->hasMany(CustomerPackage::class, 'package_id');
    }

    public function details()
    {
        return $this->hasMany(PackageDetail::class, 'package_id');
    }
}
