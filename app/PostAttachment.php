<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
	protected $table = 'post_attachment';	

	public function post_img()
	{
		return $this->belongsTo(Post::class, 'post_id', 'id');
	}


   
}
