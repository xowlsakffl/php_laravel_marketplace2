<?php

namespace App;

use App\Scopes\SellerScope;

class Seller extends User
{
    protected static function boot()//부팅 메서드는 인스턴스가 생성될 때 호출
    {
        parent::boot();

        static::addGlobalScope(new SellerScope);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
