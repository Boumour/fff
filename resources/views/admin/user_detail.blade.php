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
          <a href="{{ url('/admin/users-list') }}" class="btn btn-info pull-right"><i class="fa fa-step-backward"></i> Back</a>
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
                  <li class="active"><a href="#details" data-toggle="tab">Details</a></li>
                  <li><a href="#cvr_img" data-toggle="tab">Image</a></li>
                </ul>
              </div>

              <div class="col-xs-9">
                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="tab-pane active" id="details">
                    <table class="table table-striped table-bordered">
                      <label>User Details: </label>
                      <tr>
                           <td>Name</td>
                           <td>{!! !empty($user->full_name) ? $user->full_name : '<span class="label label-danger">None</span>' !!}</td>
                        </tr>
                        <tr>
                           <td>Email</td>
                           <td>{!! !empty($user->email) ? $user->email : '<span class="label label-danger">None</span>' !!}</td>
                        </tr>
                        <tr>
                           <td>User Name</td>
                           <td>{!! !empty($user->username) ? $user->username : '<span class="label label-danger">None</span>' !!}</td>
                        </tr>
                        <tr>
                           <td>Email Verified</td>
                           @if($user->emailVerified == 1)
                              <td><span class="label label-success">Verified</span></td>
                           @else
                              <td><span class="label label-warning">No</span></td>
                           @endif
                        </tr>
                        <tr>
                           <td>Facebook Linked</td>
                           @if($user->facebookLinked == 1)
                              <td><span class="label label-success">Yes</span></td>
                           @else
                              <td><span class="label label-warning">No</span></td>
                           @endif
                        </tr>
                        <tr>
                           <td>twitter Linked</td>
                           @if($user->twitterLinked == 1)
                              <td><span class="label label-success">Yes</span></td>
                           @else
                              <td><span class="label label-warning">No</span></td>
                           @endif
                        </tr>
                    </table>
                  </div>

                  <div class="tab-pane" id="cvr_img">
                      <label>Images: </label> <br>
                      <table class="table table-striped table-bordered">
                      <tr>
                        <td>Profile Image</td>
                        @if($user->profile_pic != '')
                           <?php if(strpos($user->profile_pic, "facebook") !== false) { ?>
                              <td><a href="{{ $user->profile_pic }}" target="_blank"><img src="{{ $user->profile_pic }}" height="80px" width="80px"></a></td>
                           <?php } else if(strpos($user->profile_pic, "twimg") !== false) {?>
                              <td><a href="{{ $user->profile_pic }}" target="_blank"><img src="{{ $user->profile_pic }}" height="80px" width="80px"></a></td>
                           <?php } else { ?>
                              <td><a href="<?php echo env('STORAGE_URL').'user/profile/'.$user->profile_pic; ?>" target="_blank"><img src="<?php echo env('STORAGE_URL').'user/profile/'.$user->profile_pic; ?>" height="80px" width="80px"></a></td>
                           <?php } ?>
                        @else
                           <td><span class="label label-danger">None</span></td>
                        @endif
                     </tr>
                     <tr>
                        <td>Cover Image</td>
                        @if($user->cover_pic != '')
                           <td><a href="<?php echo env('STORAGE_URL').'user/cover/'.$user->cover_pic; ?>" target="_blank"><img src="<?php echo env('STORAGE_URL').'user/cover/'.$user->cover_pic; ?>" height="80px" width="80px"></a></td>
                        @else
                           <td><span class="label label-danger">None</span></td>
                        @endif
                     </tr>
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