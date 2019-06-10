@extends('admin.layouts.master')

@section('container')

<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		@if(session()->has('msg'))
		<button style="display:  none;" class="btn btn-default source asp" onclick="new PNotify({
              text: '{{ session()->get('msg') }}',
              type: 'success',
              styling: 'bootstrap3',
              delay: 3000,
          });">Success</button> 
        @endif
	  <div class="page-title">
	    <div class="title_left">
	      <h3><i class="fa fa-list"></i> <?php echo $page_title ?></h3>
	    </div>      
	  </div>
	  <div class="clearfix"></div>
	  <div class="row">
	    <div class="col-md-12 col-sm-12 col-xs-12">
	      <div class="x_panel">
	      	<div class="x_title">
	            <h2>Change Password</h2>
	            <div class="clearfix"></div>
			</div>
	        <div class="x_content">
			    <form class="form-horizontal form-label-left input_mask" action="{{ route('admin-change-password-submit') }}" method="POST" data-parsley-validate novalidate enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     
					<div class="form-group {{ $errors->has('current_password') ? ' has-error' : '' }}">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Current Password*</label>
						<div class="col-md-6 col-sm-6 col-xs-6">
						  	<input type="password"   id="current_password" name="current_password" class="form-control" placeholder="Current Password" required>
							  	@if ($errors->has('current_password'))
							  	<span class="help-block">
							      	<strong>{{ $errors->first('current_password') }}</strong>
							  	</span>
								@endif
						</div>
					</div>
                      
					<div class="form-group {{ $errors->has('new_password') ? ' has-error' : '' }}">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">New Password*</label>
						<div class="col-md-6 col-sm-6 col-xs-6">
						  	<input type="password" class="form-control"  id="new_password" name="new_password" placeholder="New Password" required>
						  	@if ($errors->has('new_password'))
						      <span class="help-block">
						         	<strong>{{ $errors->first('new_password') }}</strong>
						      </span>
						    @endif
						</div>
					</div>

					<div class="form-group {{ $errors->has('confirm_password') ? ' has-error' : '' }}">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Confirm Password*</label>
						<div class="col-md-6 col-sm-6 col-xs-6">
						  	<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
						  	@if ($errors->has('confirm_password'))
						      <span class="help-block">
						          	<strong>{{ $errors->first('confirm_password') }}</strong>
						      </span>
						    @endif
						</div>
					</div>

					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
						  	<button type="submit" class="btn btn-primary">Update Password</button>
						</div>
					</div>
                </form>
			</div>
	      </div>
	    </div>
	  </div>
	</div>
</div>
<!-- /page content -->


@endsection