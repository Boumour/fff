@extends('admin.layouts.master')

@section('container')

<!-- page content -->
<div class="right_col asp_a" role="main" >
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
	  <div class="clearfix"></div>
	  <div class="row">
	    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <!-- <div class="x_title">
            <div class="clearfix"></div>
          </div> -->
          <div class="x_content">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>User Name</th>
                  <th>Environment<br>Score</th>
                  <th>Food<br>Score</th>
                  <th>Overall<br>Score</th>
                  <th>Service<br>Score</th>
                  <th>Place Name</th>
                  <th>Text</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; ?>
                @foreach($review as $reviews)
                <?php 
                  $usrnm = \DB::table('users')->where('id','=',$reviews->user_id)->first();
                  $place_nm = \DB::table('places')->where('id','=',$reviews->placeId)->first();
                ?>
                <tr>
                  <td>{{$i}}</td>
                  <td>{!! !empty($usrnm->full_name) ? $usrnm->full_name : '<span class="label label-danger">None</span>' !!}</td>
                  <td>{!! !empty($reviews->env_score) ? $reviews->env_score : '<span class="label label-danger">None</span>' !!}</td>
                  <td>{!! !empty($reviews->food_score) ? $reviews->food_score : '<span class="label label-danger">None</span>' !!}</td>
                  <td>{!! !empty($reviews->overall_score) ? $reviews->overall_score : '<span class="label label-danger">None</span>' !!}</td>
                  <td>{!! !empty($reviews->serviceScore) ? $reviews->serviceScore : '<span class="label label-danger">None</span>' !!}</td>
                  <td>{!! !empty($place_nm->name) ? $place_nm->name : '<span class="label label-danger">None</span>' !!}</td>
                  <td>
                    @if(!empty($reviews->text))
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#defaultModal_{{$reviews->id}}">View</button>
                    @else
                      <span class="label label-danger">None</span>
                    @endif
                  </td>

                  @if($reviews->status == 1)
                    <td><span class="label label-success">Approved</span></td>
                  @else
                    <td><span class="label label-warning">Pending</span></td>
                  @endif
                  <td>
                    <a class="btn btn-danger delete btn-xs" style="border-radius: 50%;" title="Delete" data-id="{{ $reviews->id }}"><i class="fa fa-remove"></i></a>
                    @if($reviews->status == 1)
                      <a class="btn btn-dark disable btn-xs" style="border-radius: 50%;" title="DisApprove" data-id="{{ $reviews->id }}">
                        <i class="fa fa-warning"></i></a>
                    @else
                      <a class="btn btn-success enable btn-xs" style="border-radius: 50%;" title="Approve" data-id="{{ $reviews->id }}">
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

@foreach($review as $new_review)

<div class="modal fade" id="defaultModal_{{$new_review->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Place Review Description</h4>
      </div>
      <div class="modal-body">
        <p>{{$new_review->text}}</p>
      </div>
    </div>
  </div>
</div>

@endforeach

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
                url:"{{url('admin/places-review-delete')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "Place Review Deleted Successfully.",
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
                url:"{{url('admin/places-review-desable')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "Place Review Disabled Successfully.",
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
                url:"{{url('admin/places-review-enable')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "Place Review Enabled Successfully.",
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