@extends('admin.layouts.master')

@section('container')

<!-- page content -->
<div class="right_col asp_a" role="main">
	<div class="">
	  <div class="page-title">
	    <div class="title_left">
	      <h3><?php echo $page_title ?></h3>
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
            					<th>Email</th>
            					<th>UserName</th>
            					<th>Joined</th>
            					<th>Action</th>                          
            				</tr>
            			</thead>
                	<tbody>
                		<?php $i=1; ?>
                		@foreach($user->chunk(10) as $row)
                      @foreach($row as $users)
                      <tr>
            					<td>{{$i}}</td>
            					<td>{!! !empty($users->full_name) ? $users->full_name : '<span class="label label-danger">None</span>' !!}</td>
            					<td>{!! !empty($users->email) ? $users->email : '<span class="label label-danger">None</span>' !!}</td>
            					<td>{!! !empty($users->username) ? $users->username : '<span class="label label-danger">None</span>' !!}</td>
            					<td>{!! !empty($users->created_at) ? $users->created_at : '<span class="label label-danger">None</span>' !!}</td>
            					<td>
                        <a target="_blank" href="{{ url('admin/view-users-detail', $users->id) }}" class="btn btn-primary btn-xs" style="border-radius: 50%;" title="View"><i class="fa fa-eye"></i></a>
            						<a class="btn btn-danger delete btn-xs" style="border-radius: 50%;" title="Delete" data-id="{{ $users->id }}"><i class="fa fa-remove"></i></a>
            						@if($users->is_desable == 0)
  	                      <a class="btn btn-dark disable btn-xs" style="border-radius: 50%;" title="Disable" data-id="{{ $users->id }}">
  	                        <i class="fa fa-warning"></i></a>
  	                    @else
  	                      <a class="btn btn-success enable btn-xs" style="border-radius: 50%;" title="Enable" data-id="{{ $users->id }}">
  	                        <i class="fa fa-check-circle"></i></a>
  	                    @endif
            					</td>	                          
                  	</tr> 
                  	<?php $i++; ?>
                    @endforeach
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
                url:"{{url('admin/users-delete')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "User Deleted Successfully.",
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
                url:"{{url('admin/users-desable')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "User Disabled Successfully.",
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
                url:"{{url('admin/users-enable')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "User Enabled Successfully.",
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