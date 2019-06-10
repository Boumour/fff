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
          						  <th>Type</th>
                        <th>Version</th>
                        <th>Status</th>
          						  <th>Action</th>                          
          						</tr>
          					</thead>
                  	<tbody>
                  		<?php $i=1; ?>
                  		@foreach($data as $datas)
                      <tr>
                        <td>{{$i}}</td>
                        <td>{!! !empty($datas->app_type) ? $datas->app_type : '<span class="label label-danger">None</span>' !!}</td>
                        <td>{!! !empty($datas->app_version) ? $datas->app_version : '<span class="label label-danger">None</span>' !!}</td>
                        @if($datas->Is_update == 1)
                          <td><span class="label label-danger">Forcefully</span></td>
                        @else
                          <td><span class="label label-primary">Not Forcefully</span></td>
                        @endif
                        <td>
                          <a class="btn btn-primary btn-xs" href="#edit{{$datas->id}}" data-toggle="modal" style="border-radius: 50%;" title="Edit"><i class="fa fa-edit"></i></a>
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
      	<form class="form-horizontal form-label-left" action="{{ url('admin/app-version-submit') }}" method="POST" data-parsley-validate novalidate id="updateData" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Edit Application Version</h4>
        </div>
        <div class="modal-body">
      		<input type="hidden" name="_token" value="{{ csrf_token() }}">
      		<input type="hidden" name="id" value="{{$newdata->id}}">
      		<div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">App Type</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" required class="form-control" disabled placeholder="App Type" id="app_type" name="app_type" value="{{ $newdata->app_type }}">                         
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">App Version</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" required class="form-control" placeholder="App Version" id="app_version" name="app_version" value="{{ $newdata->app_version }}">                         
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <select class="form-control show-tick" name="Is_update" id="Is_update">
                    <option value="0" <?php if ($newdata->Is_update == 0) { ?>selected="selected"<?php } ?>>Not Forcefully </option>
                    <option value="1" <?php if ($newdata->Is_update == 1) { ?>selected="selected"<?php } ?>>Forcefully update</option>

                </select>
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

<!-- /page content -->

<script type="text/javascript">
$(document).ready(function() {
  $("#addData").validate({ });
  $("#updateData").validate({ });

});
</script>

@endsection