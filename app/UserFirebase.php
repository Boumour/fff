<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFirebase extends Model
{
    protected $table = 'user_firebase';

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function followers()
    {
    	return $this->hasMany(Followers::class, 'user_id', 'id')->where('status',1);
    }

    public function followings()
    {
    	return $this->hasMany(Following::class, 'user_id', 'id')->where('status',1);
    }

}
