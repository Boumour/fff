<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTags extends Model
{
    protected $table = 'post_tags';

    public function post_tags()
    {
    	return $this->belongTo(Post::class, 'post_id', 'id');
    }
}
