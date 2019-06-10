@extends('admin.layouts.master')

@section('container')

<!-- page content -->
<div class="right_col" role="main" style="height: 606px;">
	<div class="">
	  <div class="page-title">
	    <div class="title_left">
	      <h3><?php echo $page_title ?></h3>
	    </div> 
	    <div class="title_right">
          <a href="{{ url('admin/blog-list') }}" class="btn btn-info pull-right"><i class="fa fa-step-backward"></i> Back</a>
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
			    <form class="form-horizontal form-label-left input_mask" action="{{ url('admin/submit-blog') }}" method="POST" novalidate enctype="multipart/form-data">
		        <input type="hidden" name="_token" value="{{ csrf_token() }}">
		        <input type="hidden" name="id" value="{{ $id }}">
		          <div class="form-group">
		            <label class="control-label col-md-2 col-sm-2 col-xs-12">Title: </label>
		            <div class="col-md-10 col-sm-10 col-xs-12">
		                <input type="text" required class="form-control" placeholder="Blog Title" id="title" name="title" value="{{ $title }}">                         
		            </div>
		          </div>

		          <div class="form-group">
		            <label class="control-label col-md-2 col-sm-2 col-xs-12">Cover Image: </label>
		            <div class="col-md-10 col-sm-10 col-xs-12">
		                <input type="file" name="image" accept="image/*"> 
		                @if($image!='' || $image != NULL)
		                   <img src="<?php echo env('STORAGE_URL')."blog/".$image; ?>" height="80px" width="100px">
		                @endif
		            </div>
		          </div>


		          <div class="form-group">
		          	<label class="control-label col-md-2 col-sm-2 col-xs-12">Text: </label> <br><br>
		          	<!-- <div class="col-md-9 col-sm-9 col-xs-12"> -->
		            	<textarea id="elm1" name="text" required>{{ $text }}</textarea>
		            <!-- </div> -->
		          </div>

		          <div class="form-group">
		            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-0">
		              <button type="submit" class="btn btn-primary">Save</button>
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

<script type="text/javascript">
    $(document).ready(function () {
      if($("#elm1").length > 0){
        tinymce.init({
            selector: "textarea#elm1",
            theme: "modern",
            height:400,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
            style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ]
        });
      }
    });
</script>

@endsection