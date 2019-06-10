@extends('admin.layouts.master')

@section('container')

<!-- page content -->
<div class="right_col asp_a" role="main">
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
          <a href="{{ url('/admin/edit-places', 0) }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New</a>
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
                  <th>Name</th>
                  <th>Country</th>
                  <th>City</th>
                  <th>Type</th>
                  <th>UserName</th>
                  <th>Status</th>
                  <th>Action</th>                          
                </tr>
              </thead>
              <tbody>
                <?php $i=1; ?>
                @foreach($data->chunk(100) as $chunks)
                @foreach ($chunks as $datas)
                <tr>
                  <td>{{$i}}</td>
                  <td>{!! !empty($datas->name) ? $datas->name : '<span class="label label-danger">None</span>' !!}</td>
                  <td>{!! !empty($datas->country) ? $datas->country : '<span class="label label-danger">None</span>' !!}</td>
                  <td>{!! !empty($datas->city) ? $datas->city : '<span class="label label-danger">None</span>' !!}</td>
                  <td>{!! !empty($datas->type) ? $datas->type : '<span class="label label-danger">None</span>' !!}</td>
                  <td>{!! !empty($datas->username) ? $datas->username : '<span class="label label-danger">None</span>' !!}</td>
                  @if($datas->queued == 1)
                    <td><span class="label label-success">Approved</span></td>
                  @else
                    <td><span class="label label-warning">Pending</span></td>
                  @endif
                  
                  <td>
                      <a target="_blank" class="btn btn-primary btn-xs" href="{{ url('/admin/places-details', $datas->id) }}" style="border-radius: 50%;"><i class="fa fa-eye"></i></a>
                      <a class="btn btn-primary btn-xs" href="{{ url('/admin/edit-places', $datas->id) }}" style="border-radius: 50%;" title="Edit"><i class="fa fa-edit"></i></a>
                      <a class="btn btn-danger delete btn-xs" style="border-radius: 50%;" title="Delete" data-id="{{ $datas->id }}"><i class="fa fa-remove"></i></a>
                      @if($datas->queued == 1)
                        <a class="btn btn-dark disable btn-xs" style="border-radius: 50%;" title="DisApprove" data-id="{{ $datas->id }}">
                      <i class="fa fa-warning"></i></a>
                      @else
                        <a class="btn btn-success enable btn-xs" style="border-radius: 50%;" title="Approve" data-id="{{ $datas->id }}">
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
                url:"{{url('admin/places-delete')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "Place Deleted Successfully.",
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
                url:"{{url('admin/places-desable')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "Places Disabled Successfully.",
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
                url:"{{url('admin/places-enable')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "Places Enabled Successfully.",
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