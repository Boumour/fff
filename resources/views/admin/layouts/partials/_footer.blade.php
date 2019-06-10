<!-- footer content -->
  <footer>
    <div class="pull-right">
      <?php echo env('APP_NAME'); ?>
    </div>
    <div class="clearfix"></div>
  </footer>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('assets/vendors/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('assets/vendors/nprogress/nprogress.js') }}"></script>
    <script src="{{ asset('assets/vendors/iCheck/icheck.min.js') }}"></script>
    <!-- Custom Theme Scripts -->
    <script src="{{ asset('assets/js/custom.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
    <!-- PNotify -->
    <script src="{{asset('assets/vendors/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('assets/vendors/pnotify/dist/pnotify.callbacks.js')}}"></script>
    <script src="{{asset('assets/vendors/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('assets/vendors/pnotify/dist/pnotify.confirm.js')}}"></script>
    <script src="{{asset('assets/vendors/pnotify/dist/pnotify.animate.js')}}"></script>
    <!-- Switchery -->
    <script src="{{asset('assets/vendors/switchery/dist/switchery.min.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>

    <!-- Richtext box js -->
    <script src="{{ asset('assets/vendors/tinymce/tinymce.min.js')}}"></script>
    <!-- jQuery custom content scroller -->
    <script src="{{ asset('assets/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')}}"></script>

    <style type="text/css">
        .switchery{
            height: 21px;
            width: 34px;
        }
        .switchery small {
            height: 21px;
            width: 21px;
        }
        .asp_a{
            min-height:931px !important;
        }
        .asp_ap{
            min-height: 1300px !important;
        }
        /*.nav-md .container .body .right_col .left_col{
            min-height:100vh !important;
        }*/
    </style>

<script type="text/javascript">
    $(document).ready(function (){
        $('.ui-pnotify').remove();
        jQuery('.asp').click();
        // $('.right_col').css({'min-height':"100%"});
   });
</script>

    
<!-- /footer content -->