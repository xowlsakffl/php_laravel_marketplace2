<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified', 'verification_token', 'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setNameAttribute($name)//변경자(Mutators) : 모델에 데이타를 입력할 때 자동으로 속성값을 설정하는 기능
    {
        $this->attributes['name'] = Str::lower($name);
    }

    public function setEmailAttribute($email)//변경자(Mutators) : 모델에 데이타를 입력할 때 자동으로 속성값을 설정하는 기능
    {
        $this->attributes['email'] = Str::lower($email);
    }

    public function getNameAttribute($name)//접근자(Accessor) : 모델에서 데이타를 가져올때 자동으로 속성을 변환하는 기능
    {
        return ucwords($name);
    }

    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    public function isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }

    public static function generateVerificationCode()
    {
        return Str::random(40);
    }
}
