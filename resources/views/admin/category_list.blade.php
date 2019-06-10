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
	    <div class="title_right">
            <a href="#add" class="btn btn-success pull-right" data-toggle="modal"><i class="fa fa-plus"></i> New</a>
        </div>
	  </div>
	  <div class="clearfix"></div>
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
							  <th>Name</th>
							  <th>Action</th>                          
							</tr>
						</thead>
                      	<tbody>
                      		<?php $i=1; ?>
                      		@foreach($category as $cat)
	                        <tr>
	                          <td>{{$i}}</td>
	                          <td>{{$cat->name}}</td>
	                          <td><a class="btn btn-primary btn-xs" href="#edit{{$cat->id}}" data-toggle="modal" style="border-radius: 50%;" title="Edit">
	                          	<i class="fa fa-edit"></i></a>
	                          	@if($cat->status == 1)
                          		<a class="btn btn-danger disable btn-xs" style="border-radius: 50%;" title="Disable" data-id="{{ $cat->id }}">
                          		<i class="fa fa-remove"></i></a>
                          		@else
                          		<a class="btn btn-success enable btn-xs" style="border-radius: 50%;" title="Enable" data-id="{{ $cat->id }}">
                          		<i class="fa fa-check-circle"></i></a>
                          		@endif
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

<!-- UPDATE MODEL -->
@foreach($category as $newcat)
<div class="modal fade Edit" tabindex="-1" id="edit{{$newcat->id}}" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
      	<form class="form-horizontal form-label-left" action="{{ url('admin/category-submit') }}" method="POST" data-parsley-validate novalidate id="updateData">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Edit Category</h4>
        </div>
        <div class="modal-body">
      		<input type="hidden" name="_token" value="{{ csrf_token() }}">
      		<input type="hidden" name="id" value="{{$newcat->id}}">
      		<div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Category*</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" required class="form-control" placeholder="Category Name" id="name" name="name" value="{{ $newcat->name }}">                         
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
        </form>
      </div>
    </div>
</div>
@endforeach
<!-- CLOSE UPDATE MODEL -->

<!-- ADD MODEL -->
<div class="modal fade add" tabindex="-1" id="add" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
      	<form class="form-horizontal form-label-left" action="{{ url('admin/category-submit') }}" method="POST" data-parsley-validate novalidate id="addData">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Add Category</h4>
        </div>
        <div class="modal-body">
      		<input type="hidden" name="_token" value="{{ csrf_token() }}">
      		<input type="hidden" name="id" value="0">
      		<div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Category*</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" required class="form-control" placeholder="Category Name" id="name" name="name">                         
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
        </form>
      </div>
    </div>
</div>
<!-- CLOSE ADD MODEL -->


<!-- /page content -->

<script type="text/javascript">
$(document).ready(function() {
	$("#addData").validate({ });
    $("#updateData").validate({ });

	$(".disable").click(function() {
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
                url:"{{url('admin/category-disable')}}?id="+id,
                success:function(res){
	                  new PNotify({
	                  	  type: 'success',
						  text: "Category Disabled Successfully.",
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

	$(".enable").click(function() {
	var id = $(this).attr('data-id');
	new PNotify({
              title: 'Confirmation Needed',
              text: 'Are you sure?',
              type: 'warning',
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
                url:"{{url('admin/category-enable')}}?id="+id,
                success:function(res){
	                  new PNotify({
	                  	  type: 'success',
						  text: "Category Enabled Successfully.",
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