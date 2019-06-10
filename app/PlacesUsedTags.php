<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlacesUsedTags extends Model
{
	protected $table = 'places_used_tag';

   public function place_use_tags()
    {
    	return $this->belongsTo(Places::class, 'places_id', 'id');
    }
}
