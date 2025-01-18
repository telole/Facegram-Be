<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected  $guarded = ['id'];

    protected $table = 'posts';

    public function User() {
        return $this->belongsTo(User::class, 'user_id', 'id');

    }

    public $timestamps = false;

    public function postsa() {
        return $this->hasMany(PostAttachment::class, 'post_id', 'id');}
    
}
