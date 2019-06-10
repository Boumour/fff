<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpeningHours extends Model
{
	protected $table = 'opening_hours';

	public function openig_hours($value='')
	{
		return $this->belongsTo(Places::class, 'places_id', 'id');
	}
   
}
