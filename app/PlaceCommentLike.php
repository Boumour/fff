<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlaceCommentLike extends Model
{
    protected $table = 'place_comment_like';

    public function place_comment_likes()
    {
    	return $this->belongsTo(Place::class, 'id', 'place_id');
    }
}
