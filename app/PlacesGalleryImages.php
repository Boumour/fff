<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlacesGalleryImages extends Model
{
	protected $table = 'places_gallery_images';

	public function places_gal_img()
	{
		return $this->belongsTo(Places::class, 'id', 'places_id');
	}
}
