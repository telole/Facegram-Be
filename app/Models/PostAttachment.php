<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    //
    protected $table = 'post_attachments';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function posts() {
        return $this->belongsTo(Post::class, 'post_id', 'id'); 

    }
}
