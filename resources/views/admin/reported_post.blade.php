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
                  <th>UserName</th>
                  <th>Reported <br>By User</th>
                  <th>Report Type</th>
                  <th>Post Image</th>
                  <th>Text</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; ?>
                @foreach($data as $datas)
                <?php 
                  $usrnm = \DB::table('users')->where('id','=',$datas->user_id)->first();                  
                ?>
                <tr>
                  <td>{{$i}}</td>
                  <td>{!! !empty($usrnm->username) ? $usrnm->username : '<span class="label label-danger">None</span>' !!}</td>
                  <td>{!! !empty($datas->reported_by) ? $datas->reported_by : '<span class="label label-danger">None</span>' !!}</td>
                  <td>{!! !empty($datas->r_type) ? $datas->r_type : '<span class="label label-danger">None</span>' !!}</td>
                  <td>
                    @if(!empty($datas->post_pic))
                      <a href="<?php echo env('STORAGE_URL')."post/".$datas->post_pic; ?>" target="_blank"><img src="<?php echo env('STORAGE_URL')."post/".$datas->post_pic; ?>" style="height: 60px; width: 60px;"></a>
                    @else
                      <span class="label label-danger">None</span>
                    @endif
                  </td>
                  <td>
                    @if(!empty($datas->text))
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#defaultModal_{{$datas->id}}">View</button>
                    @else
                      <span class="label label-danger">None</span>
                    @endif
                  </td>
                  <td>
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

@foreach($data as $new_data)

<div class="modal fade" id="defaultModal_{{$new_data->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Reported Post Description</h4>
      </div>
      <div class="modal-body">
        <p>{{$new_data->text}}</p>
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
                url:"{{url('admin/delete-reported-post')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "Reported Post Deleted Successfully.",
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