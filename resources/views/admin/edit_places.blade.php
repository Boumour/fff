@extends('admin.layouts.master')

@section('container')

<style type="text/css">
	.asp{
		padding-left: 0 !important; 
		padding-right: 0 !important;
	}
	.ap{
		margin-bottom: 83px !important;
    	margin-left: -27px;
	}
	img { 
	   border:1px solid #021a40;
	}
</style>

<!-- page content -->
<div class="right_col" role="main">
	<div class="">
	  <div class="page-title">
	    <div class="title_left">
	      <h3><?php echo $page_title ?></h3>
	    </div> 
	    <div class="title_right">
          <a href="{{ url('/admin/places-list') }}" class="btn btn-info pull-right"><i class="fa fa-step-backward"></i> Back</a>
      </div>     
	  </div>
	  <div class="clearfix"></div>
	  <div class="row">
	  	@if(session()->has('msg'))
		<button style="display:  none;" class="btn btn-default source asp" onclick="new PNotify({
              text: '{{ session()->get('msg') }}',
              type: 'success',
              styling: 'bootstrap3',
              delay: 3000,
          });">Success</button> 
    	@endif
	    <div class="col-md-12 col-sm-12 col-xs-12">
	      <div class="x_panel">
	      <div class="x_content">
		   <form class="form-horizontal form-label-left" action="{{ url('admin/update-places') }}" id="editfrmvaild" method="POST" data-parsley-validate novalidate enctype="multipart/form-data">
		   	<input type="hidden" name="_token" value="{{ csrf_token() }}">
      		<input type="hidden" name="id" value="{{ $id }}">

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" required id="name" placeholder="Place Name" name="name" value="{{ $name }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">UserName</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Place UserName" name="username" value="{{ $username }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">country</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Place Country" name="country" value="{{ $country }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">State</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Place State" name="state" value="{{ $state }}">
				</div>
				</div>
				
				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">City</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Place City" name="city" value="{{ $city }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Address line1</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Address line1" name="line1" value="{{ $line1 }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Address line2</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Address line2" name="line2" value="{{ $line2 }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Address Street</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Address Street" name="street" value="{{ $street }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Full Address</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<textarea class="form-control" placeholder="Full Address" name="addressFull" style="height: 120px !important;">{{ $addressFull }}</textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Full Bio</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<textarea class="form-control" placeholder="Full BIO" name="bio" style="height: 120px !important;">{{ $bio }}</textarea>
				</div>
				</div> <br>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Categories</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<?php 
						$cat_id = array();
						if ($categories != '')
						{
							$dataId = explode(',', $categories);
							$cat_id = \DB::table('place_category')->whereIn('id',$dataId)->pluck('id')->toArray();
						}
					?>
					@foreach($cat as $cats)
					<div class="col-md-4">
					<label>
					  <input type="checkbox" name="categories[]" required class="js-switch" value="{{ $cats->id }}" 
					  <?php if(in_array($cats->id, $cat_id)) {  ?> checked="checked" <?php } ?> > {{ $cats->name }} 
					</label>
					</div>
					@endforeach
					<label class="error" for="categories[]" style="margin-left: 10px;"></label>
				</div>
				</div> <br>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Place Tags</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<?php 
						$data = array();
						$data = \DB::table('places_used_tag')->where('places_id','=',$id)
										->pluck('places_tag_id')->toArray();
					?>
					@foreach($plac_tag as $tags)
					<div class="col-md-4">
					<label>
						<input type="checkbox" name="used_tag[]" class="js-switch" value="{{ $tags->id }}" 
						<?php if(in_array($tags->id, $data)) {  ?> checked="checked" <?php } ?>> 
						{{ $tags->name }} 
					</label>
					</div>
					@endforeach
				</div>
				</div> <br>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Email" name="email" value="{{ $email }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Facebook Link</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Facebook Link" name="facebookLink" value="{{ $facebookLink }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Twitter Link</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Twitter Link" name="twitterLink" value="{{ $twitterLink }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Website Link</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Website Link" name="websiteLink" value="{{ $websiteLink }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Youtube Link</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Youtube Link" name="youtubeLink" value="{{ $youtubeLink }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Featured Video Link</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Featured Video Link" name="featuredVideoLink" value="{{ $featuredVideoLink }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Phone Number</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Phone Number" name="phoneNumber" value="{{ $phoneNumber }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Phone Number2</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Phone Number2" name="phoneNumber2" value="{{ $phoneNumber2 }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Postal Code</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Postal Code" name="postal" value="{{ $postal }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Postal City</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Postal City" name="postalCity" value="{{ $postalCity }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Postal Line1</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Postal Line1" name="postalLine1" value="{{ $postalLine1 }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Postal Line2</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Postal Line2" name="postalLine2" value="{{ $postalLine2 }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Postal Postcode</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Postal Postcode" name="postalPostcode" value="{{ $postalPostcode }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Postal State</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Postal State" name="postalState" value="{{ $postalState }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Short Bio</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<textarea class="form-control" placeholder="Enter Short Bio" name="shortBio" style="height: 120px !important;">{{ $shortBio }}</textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Slug</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter slug" name="slug" value="{{ $slug }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Type</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Type" name="type" value="{{ $type }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Country2</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Country2" name="country2" value="{{ $country2 }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Postal Country</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Postal Country" name="postalCountry" value="{{ $postalCountry }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Environmental Score</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Environmental Score" name="environmentalScore" value="{{ $environmentalScore }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Food Score</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Food Score" name="foodScore" value="{{ $foodScore }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Overall Score</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Overall Score" name="overallScore" value="{{ $overallScore }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Service Score</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Service Score" name="serviceScore" value="{{ $serviceScore }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Google PlaceID</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Google PlaceID" name="googlePlaceID" value="{{ $googlePlaceID }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Latitued</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Latitued" name="lat" value="{{ $lat }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Longitude</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Enter Longitude" name="lon" value="{{ $lon }}">
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Cover Image</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="file" name="image" accept="image/*">
					@if(!empty($image->cover_pic))
					<img src="<?php echo env('STORAGE_URL').'places/cover/'.$image->cover_pic; ?>" height="80px" width="80px">
					@endif
				</div>
				</div>

				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Opening Hours</label>
				<?php 
					if ($details != '') {
						$data = \DB::table('opening_hours')->where('places_id',$id)->get();
						$hour_cnt = count($data);
						$i=1;
						foreach ($data as $value) { ?>
							<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-7 col-sm-9 col-xs-12">
								<input type="text" class="form-control" placeholder="Opening Hours" name="details[]" id="details" value="{{ $value->details }}">
								<input type="hidden" name="o_hour[]" value="{{$value->id}}">
							</div>
							@if(count($data) == $i)
							<div class="col-md-2 col-sm-9 col-xs-12">
								<input type="hidden" name="hour_id" id="hour_id" value="{{ $hour_cnt }}">
								<span id="newone" title="Add New" class="btn bg-black waves-light"><i class="fa fa-plus-square fa-2x"></i></span>
							</div>
							@endif

							</div>
							
				<?php $i++;	} ?> 
							
				<?php	} else { ?>			
							<div class="col-md-7 col-sm-9 col-xs-12">
								<input type="text" class="form-control" placeholder="Opening Hours" name="details[]" id="details" >
							</div>

							@if($id == 0)
							<div class="col-md-2 col-sm-4 col-xs-12">
				                <span id="addnewbtn" title="Add New" class="btn bg-black waves-light"><i class="fa fa-plus-square fa-2x"></i></span>
			                </div>
			                @endif
					<?php } ?>
							@if($id > 0 && empty($details))
							<div class="col-md-2 col-sm-4 col-xs-12">
				                <span id="addnewbtn" title="Add New" class="btn bg-black waves-light"><i class="fa fa-plus-square fa-2x"></i></span>
			                </div>
			                @endif
				

				</div>
				
				<div class="form-group">
					<div id="Myappenddiv">
	                </div>
	            </div>

	            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Gallery Image</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<input type="file" name="gallery_img[]" accept="image/*" multiple>
				</div>
				</div>

				@if($gallery_img != '')
				<?php 
					$all = \DB::table('places_gallery_images')->where('places_id','=',$id)->where('deleted_at',0)->get();
				?>
				<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<div class="bs-example" data-example-id="simple-jumbotron">
						<div class="jumbotron asp">
							<?php foreach($all as $alls) 
							{
								if (strpos($alls->storageUrl, "firebasestorage") !== false) {
								?>
									<input type="hidden" name="gal_id" value="{{$alls->id}}">
									<img src="{{ $alls->storageUrl }}" height="80px" width="80px" hspace="15" vspace="20">
									<a class="btn btn-danger delete btn-xs ap" style="border-radius: 50%;" title="Delete" data-id="{{ $alls->id }}"><i class="fa fa-remove"></i></a>
								<?php } else { ?>
									<input type="hidden" name="gal_id" value="{{$alls->id}}">
								    <img src="<?php echo env('STORAGE_URL').'places/gallery/'.$alls->storageUrl; ?>" height="80px" width="80px" hspace="15" vspace="20">
								    <a class="btn btn-danger delete btn-xs ap" style="border-radius: 50%;" title="Delete" data-id="{{ $alls->id }}"><i class="fa fa-remove"></i></a>
								<?php } ?>
				 		<?php } ?>
						</div>
					</div>
	            </div>
	       		</div>
	       		@endif

				<div class="ln_solid"></div>
				<div class="form-group">
				<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
				 	<button type="submit" class="btn btn-success">Update Place</button>
				</div>
				</div><br><br>

         </form>
			</div>
	      </div>
	    </div>
	  </div>
	</div>
</div>
<!-- /page content -->

<script type="text/javascript">

	$(document).ready(function() {
        var max_fields      = 7; 
        var add_button      = $("#addnewbtn"); 
        var x = 1; 
        $(add_button).click(function(e){ 
            e.preventDefault();
            if(x < max_fields){ 
                x++; 
                $("#Myappenddiv").append('<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label><div class="col-md-7 col-sm-9 col-xs-12"><input type="text" class="form-control" placeholder="Opening Hours" name="details[]" id="details" value="{{ $details }}"></div></div>');
            }
        });

        var max      = 7; 
        var newone      = $("#newone"); 
        var hidden = $('#hour_id').val();
        if (hidden < 7) {
        	$(newone).click(function(e){ 
            e.preventDefault();
            if(hidden < max){ 
                hidden++; 
                $("#Myappenddiv").append('<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label><div class="col-md-7 col-sm-9 col-xs-12"><input type="text" class="form-control" placeholder="Opening Hours" name="details[]" id="details"></div></div>');
            	}
        	});
        }

    });

 $(document).ready(function() {
  $(".delete").click(function() {
  var id = $(this).attr('data-id');
  new PNotify({
              title: 'Confirmation Needed',
              text: 'Are you sure?',
              type: 'error',
              styling: 'bootstrap3',
              animate: 'slow',
              icon: 'glyphicon glyphicon-question-sign',
              hide: false,
              confirm: {
          confirm: true
        },
            }).get().on('pnotify.confirm', function(){
                $.ajax({
                type:"get",
                url:"{{url('admin/places-gallery-delete')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "Image Deleted Successfully.",
              styling: 'bootstrap3',
              delay: 1500,
            });
            setTimeout(function() {
                   window.location.reload();
                }, 2000);
                  }
              }); 
            });
    });

});
</script>

@endsection