<div class="col-md-3 left_col">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
      <a href="{{route('admin.dashboard')}}" class="site_title text-center"> <span>Fair Food Forager</span></a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
      <div class="profile_pic">
        <img src="{{ asset(env('APP_LOGO')) }}" alt="..." class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Welcome,</span>
        <h2>Admin</h2>
      </div>
      <div class="clearfix"></div>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
          <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-home"></i> Home</a></li>  
          <li><a href="{{route('admin.category.list')}}"><i class="fa fa-list"></i> Category</a></li>
          <li><a href="{{route('admin.place.tags.list')}}"><i class="fa fa-tags"></i> Place Tags</a></li>
          <li class="{{{ (Request::is('admin/users-list') ? 'active' : '') }}} {{{ (Request::segment(2) == 'view-users-detail' ? 'active' : '') }}}"><a href="{{route('admin.users.list')}}"><i class="fa fa-user"></i> Users list </a> </li>
          <li class="{{{ (Request::is('admin/places-list') ? 'active' : '') }}} {{{ (Request::segment(2) == 'edit-places' ? 'active' : '') }}} {{{ (Request::segment(2) == 'places-details' ? 'active' : '') }}}"><a href="{{route('admin.places.list')}}" ><i class="fa fa-plane"></i> Places</a></li>
          <!-- <li><a href="{{route('admin.places.reviews')}}"><i class="fa fa-pencil-square-o"></i> Places Review</a></li> -->
          <li><a href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i> Places Review <span class="label label-danger"> TBT </span></a></li>
          
          <li><a href="{{route('admin.our.team')}}"><i class="fa fa-users"></i> Our Team</a></li>
          <li><a href="{{route('admin.ambassador.list')}}"><i class="fa fa-users"></i> Ambassadors</a></li>
           <li><a href="{{route('admin.testimonial')}}"><i class="fa fa-text-width"></i> Testimonial</a></li>
          <!-- <li><a href="{{route('admin.suggest.venue')}}"><i class="fa fa-archive"></i> Suggested Venue</a></li>  -->
          <li><a href="javascript:void(0)"><i class="fa fa-archive"></i> Suggested Venue  <span class="label label-danger"> TBT </span></a></li>
          <li><a href="{{route('admin.media.list')}}"><i class="fa fa-music"></i> Media List</a></li>
          <li class="{{{ (Request::is('admin/blog-list') ? 'active' : '') }}} {{{ (Request::is('admin/view-blog-imgs') ? 'active' : '') }}}"><a><i class="fa fa-clone"></i>Blogs <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li class="{{{ (Request::is('admin/view-blog-imgs') ? 'active' : '') }}} "><a href="{{route('admin.blog.imgs.list')}}">Add / View Blogs Images</a></li>
              <li class="{{{ (Request::is('admin/blog-list') ? 'active' : '') }}} {{{ (Request::segment(2) == 'add-blog' ? 'active' : '') }}}"><a href="{{route('admin.blog.list')}}">Add / List Blogs</a></li>
              
              
            </ul>
          </li>
          <li><a href="{{route('admin.privacy.policy')}}"><i class="fa fa-briefcase"></i> Privacy Policy</a></li>
          <li><a href="{{route('admin.app.version')}}"><i class="fa fa-mobile"></i> Application Version</a></li>
          <li><a href="{{route('admin.setting')}}"><i class="fa fa-cogs"></i> Settings</a></li>                    
        </ul>
      </div>
    </div>
    <!-- /sidebar menu -->   
  </div>
</div>