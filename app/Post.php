<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $table = 'post';

	public function userfire()
	{
		return $this->belongsTo(User::class, 'user_id', 'id')->select(array('id', 'email','full_name'));
	}

	public function post_picture()
    {
    	return $this->hasMany(PostAttachment::class, 'post_id', 'id')->select(array('id', 'post_id','user_id', 'storageUrl','post_pic'));
    }

    public function post_like()
    {
    	return $this->hasMany(PostLike::class)->select(array('id', 'post_id', 'like_userId', 'status'))->where('status',1);
    }

    public function post_comments()
    {
    	return $this->hasMany(PostComments::class, 'post_id', 'id')->select(array('id', 'comment_uid','user_id','text','post_id'));
    }

    public function post_comm_like()
    {
    	return $this->hasMany(PostCommentLike::class, 'post_id', 'id')->where('like',1);
    }  

    public function post_tags()
    {
        return $this->hasMany(PostTags::class, 'post_id', 'id');
    }


}
