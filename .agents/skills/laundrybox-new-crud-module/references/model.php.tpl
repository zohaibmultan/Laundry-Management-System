<?php
// Reference template — copy to app/Models/<Entity>.php and adapt.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class <Entity> extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'some_count',
        'status',
        'price',
        // ...match the migration columns exactly
    ];

    protected $casts = [
        'status' => 'boolean',
        'price' => 'float',
        'some_count' => 'integer',
    ];

    // hasMany example — name the method after the related concept, not the column:
    // public function details()
    // {
    //     return $this->hasMany(<Entity>Detail::class, '<entity>_id');
    // }

    // belongsTo example:
    // public function parent()
    // {
    //     return $this->belongsTo(Parent::class, 'parent_id');
    // }
}
