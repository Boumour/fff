<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
	protected $table = 'post_like';

	public function post_likes()
	{
		return $this->belongsTo(Post::class, 'id', 'post_id');
	}

	

}
