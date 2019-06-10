@extends('admin.layouts.master')

@section('container')

<style type="text/css">
  img { 
   border:1px solid #021a40;
  }
</style>

<!-- page content -->
<div class="right_col" role="main" >
  <div class="row">
    <div class="page-title">
      <div class="title_left">
        <h3><i class="fa fa-list"></i> <?php echo $page_title ?></h3>
      </div>     
      <div class="title_right">
          <a href="{{ url('/admin/places-list') }}" class="btn btn-info pull-right"><i class="fa fa-step-backward"></i> Back</a>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content"> 
            <div class="x_content">
              <div class="col-xs-3">

                <ul class="nav nav-tabs tabs-left">
                  <li class="active"><a href="#details" data-toggle="tab">Place Details</a>
                  </li>
                  <li><a href="#cat" data-toggle="tab">Category / Used Tags</a>
                  </li>
                  <li><a href="#cvr_img" data-toggle="tab">Cover Image</a>
                  </li>
                  <li><a href="#gallery" data-toggle="tab">Place Gallery</a>
                  </li>
                  <li><a href="#opn_hrs" data-toggle="tab">Opening Hours</a>
                  </li>
                </ul>
              </div>

              <div class="col-xs-9">
                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="tab-pane active" id="details">
                    <table class="table table-striped table-bordered">
                      <label>Place Details: </label>
                      <?php foreach ($data as $value) { 
                        if (!empty($value->categories)) 
                        {
                          $exp = explode(',', $value->categories);
                          $cat = \DB::table('place_category')->whereIn('id',$exp)
                                    ->where('status',1)->get();
                          foreach ($cat as $cat_val) {
                            $cat_nm[] = $cat_val->name;
                          }
                          $imp = implode(', ', $cat_nm);
                        } ?>

                      <tr>
                        <td>Name</td>
                        <td>{!! !empty($value->name) ? $value->name : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>UserName</td>
                        <td>{!! !empty($value->username) ? $value->username : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Country</td>
                        <td>{!! !empty($value->country) ? $value->country : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>State</td>
                        <td>{!! !empty($value->state) ? $value->state : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>City</td>
                        <td>{!! !empty($value->city) ? $value->city : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Address line1</td>
                        <td>{!! !empty($value->line1) ? $value->line1 : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Address line2</td>
                        <td>{!! !empty($value->line2) ? $value->line2 : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Street</td>
                        <td>{!! !empty($value->street) ? $value->street : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Full Address</td>
                        <td>{!! !empty($value->addressFull) ? $value->addressFull : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Bio</td>
                        <td>{!! !empty($value->bio) ? $value->bio : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Email</td>
                        <td>{!! !empty($value->email) ? $value->email : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Phone Number</td>
                        <td>{!! !empty($value->phoneNumber) ? $value->phoneNumber : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Phone Number2</td>
                        <td>{!! !empty($value->phoneNumber2) ? $value->phoneNumber2 : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Postal</td>
                        <td>{!! !empty($value->postal) ? $value->postal : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Postal City</td>
                        <td>{!! !empty($value->postalCity) ? $value->postalCity : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Postal Line1</td>
                        <td>{!! !empty($value->postalLine1) ? $value->postalLine1 : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Postal Line2</td>
                        <td>{!! !empty($value->postalLine2) ? $value->postalLine2 : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Postal PostCode</td>
                        <td>{!! !empty($value->postalPostcode) ? $value->postalPostcode : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Postal State</td>
                        <td>{!! !empty($value->postalState) ? $value->postalState : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>ShortBio</td>
                        <td>{!! !empty($value->shortBio) ? $value->shortBio : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Slug</td>
                        <td>{!! !empty($value->slug) ? $value->slug : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Type</td>
                        <td>{!! !empty($value->type) ? $value->type : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Country2</td>
                        <td>{!! !empty($value->country2) ? $value->country2 : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Postal Country</td>
                        <td>{!! !empty($value->postalCountry) ? $value->postalCountry : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Environmental Score</td>
                        <td>{!! !empty($value->environmentalScore) ? $value->environmentalScore : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Food Score</td>
                        <td>{!! !empty($value->foodScore) ? $value->foodScore : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Overall Score</td>
                        <td>{!! !empty($value->overallScore) ? $value->overallScore : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Service Score</td>
                        <td>{!! !empty($value->serviceScore) ? $value->serviceScore : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Facebook Link</td>
                        <td>{!! !empty($value->facebookLink) ? $value->facebookLink : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Twitter Link</td>
                        <td>{!! !empty($value->twitterLink) ? $value->twitterLink : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Website Link</td>
                        <td>{!! !empty($value->websiteLink) ? $value->websiteLink : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Youtube Link</td>
                        <td>{!! !empty($value->youtubeLink) ? $value->youtubeLink : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Feature Video Link</td>
                        <td>{!! !empty($value->featuredVideoLink) ? $value->featuredVideoLink : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Google PlaceID</td>
                        <td>{!! !empty($value->googlePlaceID) ? $value->googlePlaceID : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Latitude</td>
                        <td>{!! !empty($value->lat) ? $value->lat : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>

                      <tr>
                        <td>Longitude</td>
                        <td>{!! !empty($value->lon) ? $value->lon : '<span class="label label-danger">None</span>' !!}</td>
                      </tr>


                      
                      <?php } ?>
                    </table>
                  </div>

                  <div class="tab-pane" id="cat">
                    <table class="table table-striped table-bordered">
                      <label>Category / Used Tags: </label>
                        <?php
                          if (!empty($place_tag)) {
                            foreach($place_tag as $tag_val) {
                              $tags_nm[] = $tag_val->name;
                              $tag_nm = implode(', ', $tags_nm);
                            } 
                          }
                        ?>
                        <tr>
                          <td>Category</td>
                          <td>{!! !empty($imp) ? $imp : '<span class="label label-danger">None</span>' !!}</td>
                        </tr>
                        
                        <tr>
                          <td>Places Used Tags</td>
                          <td>{!! !empty($tag_nm) ? $tag_nm : '<span class="label label-danger">None</span>' !!}</td>
                        </tr>
                    </table>
                  </div>

                  <div class="tab-pane" id="cvr_img">
                      <label>Cover Image: </label> <br>
                      <table class="table table-striped table-bordered">
                        <?php 
                        if (!empty($cover->cover_pic)) { ?>
                         <a href="<?php echo env('STORAGE_URL').'places/cover/'.$cover->cover_pic; ?>" target="_blank"> <img src="<?php echo env('STORAGE_URL').'places/cover/'.$cover->cover_pic; ?>" height="150px" width="200px"></a>
                        <?php } else if (!empty($cover->storageUrl)) { ?>
                          <img src="<?php echo $cover->storageUrl; ?>" height="150px" width="200px">
                        <?php } else { ?>
                          <label class="label label-danger">No record found!</label>
                      <?php } ?>
                      </table>
                  </div>

                  <div class="tab-pane" id="gallery">
                    <label>Gallery Images: </label> <br>
                      <table class="table table-striped table-bordered">
                        <?php 
                          if (count($gallery)>0) {
                            foreach ($gallery as $gal_val) {
                              if (strpos($gal_val->storageUrl, "firebasestorage") !== false) { ?>
                                <a href="{{ $gal_val->storageUrl }}" target="_blank"> <img src="{{ $gal_val->storageUrl }}" height="150px" width="200px" hspace="15" vspace="20"></a>
                              <?php } else if($gal_val->storageUrl != '') { ?>
                                <a href="<?php echo env('STORAGE_URL').'places/gallery/'.$gal_val->storageUrl; ?>" target="_blank"><img src="<?php echo env('STORAGE_URL').'places/gallery/'.$gal_val->storageUrl; ?>" height="150px" width="200px" hspace="15" vspace="20"></a>
                              <?php } else { ?>
                                <label class="label label-danger">No record found!</label>
                                <?php 
                              }
                            }
                          }else{ ?>
                            <label class="label label-danger">No record found!</label>
                         <?php } ?>
                      </table>
                  </div>
                  
                  <div class="tab-pane" id="opn_hrs">
                    <label>Opening Hours: </label> <br>
                      <table class="table table-striped table-bordered">
                        @if (count($hour)>0)
                        <?php foreach($hour as $hours) { ?>
                          <tr>
                            <td>{!! !empty($hours->details) ? $hours->details : '<span class="label label-danger">None</span>' !!}</td>
                          </tr>
                        <?php } ?>
                        @else
                          <label class="label label-danger">No record found!</label>
                        @endif
                      </table>
                  </div>
                </div>
              </div>

              <div class="clearfix"></div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection