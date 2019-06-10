<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
	protected $table = 'following';

	public function followings()
	{
		return $this->belongsTo(UserFirebase::class, 'id', 'user_id');
	}
   
}
