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
          <a href="#add" class="btn btn-success pull-right" data-toggle="modal"><i class="fa fa-plus"></i> New</a>
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
          						  <th>Name</th>
                        <th>Designation</th>
                        <!-- <th>Text</th> -->
          						  <th>Action</th>                          
          						</tr>
          					</thead>
                  	<tbody>
                  		<?php $i=1; ?>
                  		@foreach($data as $datas)
                      <tr>
                        <td>{{$i}}</td>
                        <td>{!! !empty($datas->name) ? $datas->name : '<span class="label label-danger">None</span>' !!}</td>
                        <td>{!! !empty($datas->designation) ? $datas->designation : '<span class="label label-danger">None</span>' !!}</td>
                        <!-- <td>{!! !empty($datas->text) ? $datas->text : '<span class="label label-danger">None</span>' !!}</td> -->
                        <td>
                          <a class="btn btn-primary btn-xs" href="#edit{{$datas->id}}" data-toggle="modal" style="border-radius: 50%;" title="Edit"><i class="fa fa-edit"></i></a>
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

<!-- UPDATE MODEL -->
@foreach($data as $newdata)
<div class="modal fade Edit" tabindex="-1" id="edit{{$newdata->id}}" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      	<form class="form-horizontal form-label-left" action="{{ url('admin/testimonial-submit') }}" method="POST" data-parsley-validate novalidate id="updateData" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Edit Testimonial</h4>
        </div>
        <div class="modal-body">
      		<input type="hidden" name="_token" value="{{ csrf_token() }}">
      		<input type="hidden" name="id" value="{{$newdata->id}}">
      		<div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Name*</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" required class="form-control" placeholder="User Name" id="name" name="name" value="{{ $newdata->name }}">                         
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Designation*</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" required class="form-control" placeholder="User Designation" id="designation" name="designation" value="{{ $newdata->designation }}">                         
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Text*</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <textarea name="text" class="form-control" placeholder="Enter Description" rows="7">{{$newdata->text}}</textarea>
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
    <div class="modal-dialog">
      <div class="modal-content">
      	<form class="form-horizontal form-label-left" action="{{ url('admin/testimonial-submit') }}" method="POST" data-parsley-validate novalidate id="addData" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Add Testimonial</h4>
        </div>
        <div class="modal-body">
      		<input type="hidden" name="_token" value="{{ csrf_token() }}">
      		<input type="hidden" name="id" value="0">
      		<div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Name*</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" required class="form-control" placeholder="User name" id="name" name="name">
            </div>
          </div>
           <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Designation*</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" required class="form-control" placeholder="Designation" id="designation" name="designation">                         
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Text*</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
               <textarea name="text" class="form-control" placeholder="Enter Description" rows="7"></textarea>
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
                url:"{{url('admin/testimonial-delete')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "Testimonial Deleted Successfully.",
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