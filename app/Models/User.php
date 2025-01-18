<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    //
    use HasApiTokens, HasFactory;
    
    protected $guarded = ['id'];    
     protected $table = 'users';
     protected $hidden = ['password'];

     public function follower() {
        return $this->hasMany(Follow::class, 'follower_id', 'id');

     }

     public function following() {
        return $this->hasMany(Follow::class, 'following_id', 'id');
     }
     
     public $timestamps = false;

     public function posts() {
        return $this->hasMany(Post::class, 'user_id','id');
     }
}
