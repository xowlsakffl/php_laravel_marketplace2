<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'description',
    ];

    protected $hidden = [
        'pivot' //피벗 테이블 제외
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
