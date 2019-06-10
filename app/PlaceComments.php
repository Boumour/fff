<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlaceComments extends Model
{
    protected $table = 'place_comments';

    public function place_comm()
    {
    	return $this->belongsTo(Places::class, 'id', 'place_id');
    }
}
