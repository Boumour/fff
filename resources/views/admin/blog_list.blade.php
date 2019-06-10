@extends('admin.layouts.master')

@section('container')

<!-- page content -->
<div class="right_col" role="main">
	<div class="row">
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
	    <div class="title_right">
          <a href="{{url('admin/add-blog', 0)}}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> New</a>
      </div>
	  </div>
	  <!-- <div class="clearfix"></div> -->
	  <div class="row">
	    <div class="col-md-12 col-sm-12 col-xs-12">
	        <div class="x_content">
			     <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">                   				
                  <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
          					<thead>
          						<tr>
          						  <th>ID</th>
          						  <th>Title</th>
                        <th>Image</th>
                        <th>Created</th>
          						  <th>Action</th>                   
          						</tr>
          					</thead>
                  	<tbody>
                  		<?php $i=1; ?>
                  		@foreach($data as $datas)
                      <tr>
                        <td>{{$i}}</td>
                        <td>{!! !empty($datas->title) ? $datas->title : '<span class="label label-danger">None</span>' !!}</td>
                        @if($datas->cover_img != '')
                          <td><a href="<?php echo env('STORAGE_URL')."blog/".$datas->cover_img; ?>" target="_blank"><img src="<?php echo env('STORAGE_URL')."blog/".$datas->cover_img; ?>" style="height: 60px; width: 80px;"></a></td>
                        @else
                          <td><span class="label label-danger">None</span></td>
                        @endif
                        <td>{!! !empty($datas->created_at) ? $datas->created_at : '<span class="label label-danger">None</span>' !!}</td>
                        <td>
                          <a class="btn btn-primary btn-xs" href="{{url('admin/add-blog', $datas->id)}}" style="border-radius: 50%;" title="Edit"><i class="fa fa-edit"></i></a>
                          <a class="btn btn-danger delete btn-xs" style="border-radius: 50%;" title="Delete" data-id="{{ $datas->id }}"><i class="fa fa-remove"></i></a>
                        </td>	                          
                    	</tr> 
                    	<?php $i++; ?>
                    	@endforeach
                   	</tbody>
                  </table>
                  </div>
                </div>
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
                url:"{{url('admin/blog-delete')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "Media Deleted Successfully.",
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