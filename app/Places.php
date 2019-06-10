<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Places extends Model
{
	protected $table = 'places';

   public function place_cover_img()
   {
   		return $this->hasMany(PlacesCoverImages::class, 'places_id', 'id')->select(array('id', 'places_id','storageUrl','cover_pic'));
   }

   public function place_gallary_img()
   {
   		return $this->hasMany(PlacesGalleryImages::class, 'places_id', 'id')->select(array('id', 'places_id','storageUrl'));
   }

   public function place_comments()
   {
   		return $this->hasMany(PlaceComments::class, 'place_id', 'id');
   }

   public function place_comm_like()
   {
   		return $this->hasMany(PlaceCommentLike::class, 'place_id', 'id')->where('like',1);
   }

   public function opening_hour()
   {
         return $this->hasMany(OpeningHours::class, 'places_id', 'id')->select(array('id', 'places_id','details'));
   }

   public function place_usd_tag()
   {
         return $this->hasMany(PlacesUsedTags::class, 'places_id', 'id');//->select(array('id', 'places_id','name'));
   }

}
