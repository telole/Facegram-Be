<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    //
    protected $guarded = ['id'];
    protected $table = 'follow';

    public $timestamps = false;

    public function following() {
        return $this->belongsTo(User::class,'following_id','id');

    }

    public function follower() {
        return $this->belongsTo(User::class,'follower_id','id');
    }
}
