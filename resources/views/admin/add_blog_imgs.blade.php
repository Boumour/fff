@extends('admin.layouts.master')

@section('container')
  
 <style type="text/css">
 	.img_pk{
        width: 72%;
        height: 157px;
        margin-top:25px;
        margin-left: 31px;
        border-radius: 3px;
        border: 4px dashed #00BCD4;
        }
    .img_pk:hover {
        background-color: #00BCD4;
        border: 4px dashed #ffffff;
        }

    .file-upload {
      background-color: #ffffff;
      width: 100%;
      margin: 0 auto;
      padding: 20px;
    }
    .file-upload-content {
      display: none;
      text-align: center;
    }
    .file-upload-input {
      position: absolute;
      margin: 0;
      padding: 0;
      width: 61%;
      height: 53%;
      outline: none;
      opacity: 0;
      cursor: pointer;
    }
    .file-upload-image {
      max-height: 200px;
      max-width: 200px;
      margin: auto;
      padding: 20px;
    }
    .tesssa{
        font-weight: 100;
        color: #15824B !important;
        padding: 40px 0;
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
          	<a href="{{url('admin/view-blog-imgs')}}" class="btn btn-info pull-right"><i class="fa fa-step-backward"></i> Back</a>
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
			    <form class="box" method="post" action="{{ url('admin/submit-blog-imgs') }}" enctype="multipart/form-data">
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="col-sm-12">
                        <div class="form-group">
                            <div class="file-upload">
                              	<div class="img_pk" style="margin-left: 12%;text-align: center;">
                                	<input class="file-upload-input pk_img_new_2" name="upload_file[]" type='file' accept="image/*"  multiple/>
                                    <h3 class="tesssa">Drop files here or click to upload Images</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
					<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
					 	<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Upload Images</button>
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