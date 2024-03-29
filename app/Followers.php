<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Followers extends Model
{
	protected $table = 'followers';

	public function follower()
	{
		return $this->belongsTo(UserFirebase::class, 'id', 'user_id');
	}
   
}
