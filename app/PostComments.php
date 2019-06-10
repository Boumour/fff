<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComments extends Model
{
    protected $table = 'post_comments';

    public function post_coment()
    {
    	return $this->belongTo(Post::class, 'post_id', 'id');
    }
}
