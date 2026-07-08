<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'service_detail_id',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function serviceDetail()
    {
        return $this->belongsTo(ServiceDetail::class, 'service_detail_id');
    }
}
