@extends('admin.layouts.master')

@section('container')

<style type="text/css">
	.thumbnail{
		height: auto !important;
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
          	<a href="{{url('admin/add-blog-imgs')}}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Images</a>
      	</div>
	  </div>
	  <div class="clearfix"></div>
	  <div class="row">
	    <div class="col-md-12 col-sm-12 col-xs-12">
	      <div class="x_panel">
	        <div class="x_content">
	        	@foreach($data as $datas)
			    <div class="col-md-55">
			    	<a class="btn btn-danger delete btn-xs" style="border-radius: 50%; margin-left: 170px; margin-block-end: -4px;" title="Delete" data-id="{{ $datas->id }}"><i class="fa fa-remove"></i></a>
                    <div class="thumbnail">
                      <div class="image view view-first">
                        <a target="_blank" href="<?php echo env('STORAGE_URL')."blog_imgs/".$datas->image; ?>"><img style="width: 100%; display: block;" src="<?php echo env('STORAGE_URL')."blog_imgs/".$datas->image; ?>"></a>

                        <input type="hidden" name="id" value="{{ $datas->id }}">
                      </div>
                    </div>
              	</div>
              	@endforeach
			</div>
	      </div>
	    </div>
	  </div>
	</div>
</div>
<!-- /page content -->

<script type="text/javascript">
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
                url:"{{url('admin/delete-blog-imgs')}}?id="+id,
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