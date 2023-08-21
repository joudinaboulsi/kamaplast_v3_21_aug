<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    

    <title>NEOCOMZ - CMS</title>
    <link rel="shortcut icon" href=""/>
    <link href="/cms/css/animate.css" rel="stylesheet">
    <link href="/cms/css/bootstrap.min.css" rel="stylesheet">
    <link href="/cms/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/cms/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/cms/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="/cms/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
       <!-- FooTable -->
    <link href="/cms/css/plugins/footable/footable.core.css" rel="stylesheet">
    <link href="/cms/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/cms/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="/cms/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    <link href="/cms/css/plugins/clockpicker/clockpicker.css" rel="stylesheet">
    <link href="/cms/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="/cms/css/plugins/cropper/cropper.min.css" rel="stylesheet">
    <link href="/cms/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    
    <!-- Summernote WYSIWYG -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">

   

    <link href="/cms/css/plugins/select2/select2.min.css" rel="stylesheet">
     <!-- Tags Input -->
    <link href="/cms/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="/cms/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
    <link href="/cms/css/style.css" rel="stylesheet">
    <link href="/cms/css/style_added.css" rel="stylesheet">
    <!-- css added manually other than the template -->
    <link href="/cms/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/cms/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">

    <script src="/cms/js/jquery-2.1.1.js"></script>
</head>

<body>
    <div id="wrapper">
        
        <!-- including the sidebar -->
        @include('cms.layouts.partials.sidebar')

        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" action="search_results.html">
                        <div class="form-group">
                            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">Welcome to NEOCOMZ</span>
                        </li>
          
                        <li>
                            <a href="{{ route('admin_logout_path') }}">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>   
                    </ul>
                </nav>
            </div> 

             @yield('content')


            <div class="footer">
                <div class="pull-right">
                    <strong> Verion 1.0.0 </strong>
                </div>

                <div>
                    <strong>Copyright</strong> <a target="_blank" href="https://www.webneoo.com"> webneoo </a> &copy; 2017 - 2018
                </div>
            </div>

        </div>

    </div> <!-- wrapper -->


    <!-- Mainly scripts -->
 

    <script src="/cms/js/bootstrap.min.js"></script>
    <script src="/cms/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/cms/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Datatables -->
    <script src="/cms/js/plugins/dataTables/datatables.min.js"></script>

     <!-- Chosen -->
    <script src="/cms/js/plugins/chosen/chosen.jquery.js"></script>

    <!-- Peity -->
    <script src="/cms/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="/cms/js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/cms/js/inspinia.js"></script>
    <script src="/cms/js/plugins/pace/pace.min.js"></script>

    <!-- GITTER -->
    <script src="/cms/js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="/cms/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="/cms/js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="/cms/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="/cms/js/plugins/toastr/toastr.min.js"></script>

    <!-- iCheck -->
    <script src="/cms/js/plugins/iCheck/icheck.min.js"></script>

    <!-- Date picker -->
    <script src="/cms/js/plugins/datapicker/bootstrap-datepicker.js"></script>

    <!-- Date range use moment.js same as full calendar plugin -->
    <script src="/cms/js/plugins/fullcalendar/moment.min.js"></script>
    
     <!-- Date range picker -->
    <script src="/cms/js/plugins/daterangepicker/daterangepicker.js"></script>

    <!-- Image cropper -->
    <script src="/cms/js/plugins/cropper/cropper.min.js"></script>

    <!-- Jquery Validate -->
    <script src="/cms/js/plugins/validate/jquery.validate.min.js"></script>

     <!-- Sweet Alert -->
    <script src="/cms/js/plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Summernote WYSIWYG -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>

    <!-- TAGS Input -->
    <script src="/cms/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

    <!-- FooTable -->
    <script src="/cms/js/plugins/footable/footable.all.min.js"></script>

    
    <!-- Ladda -->
    <script src="/cms/js/plugins/ladda/spin.min.js"></script>
    <script src="/cms/js/plugins/ladda/ladda.min.js"></script>
    <script src="/cms/js/plugins/ladda/ladda.jquery.min.js"></script>
    <!-- iCheck -->
    <script src="/cms/js/plugins/iCheck/icheck.min.js"></script>

<!--         // $(document).ready(function () {
        //     $('.i-checks').iCheck({
        //         checkboxClass: 'icheckbox_square-green',
        //         radioClass: 'iradio_square-green',
        //     });
        // });
    // open and close i-box when clicking on the header bar
  //  $(document).on('click', '.ibox-title', function(e) {   
  //   $(this).next().toggle(); // open or close i-box content
  //   $(this).find('.collapse-link i').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up'); //switch the arrows
  // }); -->

    
</body>
</html>
