<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCommentLike extends Model
{
    protected $table = 'post_comment_like';

    public function post_comm_likes()
    {
    	return $this->belongsTo(Post::class, 'id', 'post_id');
    }

}
