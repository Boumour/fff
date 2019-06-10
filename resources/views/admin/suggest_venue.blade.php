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
          						  <th>Business Name</th>
                        <th>Email</th>
                        <th>Phone No</th>
          						  <th>Action</th>                          
          						</tr>
          					</thead>
                  	<tbody>
                  		<?php $i=1; ?>
                  		@foreach($data as $datas)
                      <tr>
                        <td>{{$i}}</td>
                        <td>{!! !empty($datas->b_name) ? $datas->b_name : '<span class="label label-danger">None</span>' !!}</td>
                        <td>{!! !empty($datas->b_email) ? $datas->b_email : '<span class="label label-danger">None</span>' !!}</td>
                        <td>{!! !empty($datas->phone_no) ? $datas->phone_no : '<span class="label label-danger">None</span>' !!}</td>
                        <td>
                          <button type="button" class="btn btn-primary btn-xs" style="border-radius: 50%" title="View" data-toggle="modal" data-target="#view_{{$datas->id}}"><i class="fa fa-eye"></i></button>
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
<div class="modal fade" id="view_{{$newdata->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog ">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <?php 
            if (!empty($newdata->b_name)) {
              $model = $newdata->b_name."'s Details";
            }else{
              $model = "Suggested Venue Details";
            }
            if (!empty($newdata->place_tags)) {
                $exp = explode(',', $newdata->place_tags);
                $cat = \DB::table('place_tags')->whereIn('id',$exp)->get();
                foreach ($cat as $cat_val) {
                  $cat_nm[] = $cat_val->name;
                }
                $place_tags = implode(', ', $cat_nm);

            }
          ?>
          <h4 class="modal-title" id="myModalLabel">{{$model}}</h4>
        </div>
         <div class="modal-body" style="padding: 0px;">
            <div class="x_content">
              <table class="table table-striped table-bordered">
                <tr>
                  <td>Business Name</td>
                  <td>{!! !empty($newdata->b_name) ? $newdata->b_name : '<span class="label label-danger">None</span>' !!}</td>
                </tr>
                <tr>
                  <td>Business Email</td>
                  <td>{!! !empty($newdata->b_email) ? $newdata->b_email : '<span class="label label-danger">None</span>' !!}</td>
                </tr>
                <tr>
                  <td>Physical Address</td>
                  <td>{!! !empty($newdata->phy_add) ? $newdata->phy_add : '<span class="label label-danger">None</span>' !!}</td>
                </tr>
                <tr>
                  <td>Brand Website Url</td>
                  <td>{!! !empty($newdata->web_url) ? $newdata->web_url : '<span class="label label-danger">None</span>' !!}</td>
                </tr>
                <tr>
                  <td>Phone Number</td>
                  <td>{!! !empty($newdata->phone_no) ? $newdata->phone_no : '<span class="label label-danger">None</span>' !!}</td>
                </tr>
                <tr>
                  <td>Facebook URL</td>
                  <td>{!! !empty($newdata->facebook_url) ? $newdata->facebook_url : '<span class="label label-danger">None</span>' !!}</td>
                </tr>
                <tr>
                  <td>Instagram URL</td>
                  <td>{!! !empty($newdata->Insta_url) ? $newdata->Insta_url : '<span class="label label-danger">None</span>' !!}</td>
                </tr>
                <tr>
                  <td>Description</td>
                  <td>{!! !empty($newdata->description) ? $newdata->description : '<span class="label label-danger">None</span>' !!}</td>
                </tr>
                <tr>
                  <td>Suitable Place Tags</td>
                  <td>{!! !empty($place_tags) ? $place_tags : '<span class="label label-danger">None</span>' !!}</td>
                </tr>
              </table>
            </div>
         </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>
@endforeach
<!-- CLOSE UPDATE MODEL -->

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
                url:"{{url('admin/suggest-venue-delete')}}?id="+id,
                success:function(res){
                    new PNotify({
                        type: 'success',
              text: "Suggested Venue Deleted Successfully.",
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