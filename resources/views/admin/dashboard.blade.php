@extends('admin.layouts.master')

@section('container')

<!-- page content -->
<div class="right_col" role="main" style="height: 606px;">
	<div class="">
	  <div class="page-title">
	    <div class="title_left">
	      <h3><?php echo $page_title ?></h3>
	    </div>      
	  </div>
	  <div class="clearfix"></div>
	  <div class="row">
	    <div class="col-md-12 col-sm-12 col-xs-12">
	      <div class="x_panel">
	        <div class="x_title">
	          <h2><?php //echo $page_title ?></h2>
	          <ul class="nav navbar-right panel_toolbox">                  
	          </ul>
	          <div class="clearfix"></div>
	        </div>
	        <div class="x_content">
			    Add content to the page ...
			</div>
	      </div>
	    </div>
	  </div>
	</div>
</div>
<!-- /page content -->


@endsection