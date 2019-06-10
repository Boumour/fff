<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlacesCoverImages extends Model
{
	protected $table = 'places_cover_images';

	public function places_cvr_img()
	{
		return $this->belongsTo(Places::class, 'id', 'places_id');
	}
   
}
