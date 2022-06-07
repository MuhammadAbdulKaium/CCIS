<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>CCIS</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta id="token" name="token" value="{{ csrf_token() }}">
    <link rel="manifest" href="site.webmanifest">

    @if(!empty(getInstituteProfile()))
        <link rel="icon" href="{{url('assets/users/images/'.getInstituteProfile()->logo)}}" type="image/x-icon"/>
    @else
        <link rel="icon" href="{{url('assets/users/images/alokito_software.png')}}" type="image/x-icon"/>
    @endif
    {{--    <link rel="icon" href="{{url('assets/users/images/'.getInstituteProfile()->logo)}}" type="image/x-icon"/>--}}
    {{--<link rel="shortcut icon" href="{{url('assets/users/images/'.getInstituteProfile()->logo)}}" type="image/x-icon"/>--}}


    <link rel="apple-touch-icon" href="">
    <!-- Place favicon.ico in the root directory -->
    <link href="{{ URL::asset('template-2/css/normalize.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('template-2/css/main.css') }}" rel="stylesheet" type="text/css"/>
    {{--    <link href="{{ URL::asset('template-2/css/bootstrap.css') }}" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ URL::asset('template-2/css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('css/side-navbar.css') }}">
    <link href="{{ URL::asset('template-2/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('template-2/css/jquery.smartmenus.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">


    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/main-style-new.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/sweetAlert.css') }}" rel="stylesheet"/>

    {{--    for chart --}}

   {{--    for inventory --}}
   @if(Request::segment(1)=='inventory' || Request::segment(1)=='accounts')
    <link href="{{ URL::asset('css/loader.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/custom.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('vuejs/css/vue-multiselect.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('vuejs/css/table-sort.css')}}" rel="stylesheet" type="text/css" />
   @endif
   <script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
   <script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js"></script>
   <script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js"></script>
    @php
        $menuAccess = getMenuList();
    @endphp
    @yield('styles')
    <style>
        @php $headingFontFamily=institute_property("heading-font-family") @endphp
        @php $mainFontFamily=institute_property("main-font-family") @endphp


       @if(!empty($headingFontFamily) && !empty($mainFontFamily))
        @import url('{{$headingFontFamily->font_family()->font_link}}');
        @import url('{{$mainFontFamily->font_family()->font_link}}');

        h1 {
        font-family: '{{$headingFontFamily->font_family()->font_name}}', sans-serif !important;
        }

        html, body, p, div, h2, h3, h4, h5, h6, a, ul, ol, li, th, td, dt, dl, dd, b, strong {
            font-family: '{{$mainFontFamily->font_family()->font_name}}', sans-serif !important;
        }
        @endif


        /*///  Header font Color*/
                /*Header  Color*/
        @php $headerBgColor=institute_property("Header") @endphp
        @php $topHeaderBgColor=institute_property("top-header") @endphp

        .school-name {
            font-weight: 600;
            font-size: 25px;
            @if(!empty($headerFontColor->attribute_value))
            color:{{ $headerFontColor->attribute_value }} !important;
        @endif
        }

        .campus-name {
            margin-top: 16px;
            font-size: 18px;
            text-align: center;
            color: #fff;
            padding: 10px;
            @if(!empty($headerBgColor->attribute_value))
background:{{ $headerBgColor->attribute_value }} !important;
            @endif
            border-radius:20px;
        }

        .logo-wrap {
            @if(!empty($topHeaderBgColor->attribute_value))
background:{{ $topHeaderBgColor->attribute_value }} !important;
            @endif
             @if(!empty($headerBgColor->attribute_value))
border-top: 3px solid {{ $headerBgColor->attribute_value }} !important;
        @endif

}
        .main-menu-style {
            @if(!empty($headerBgColor->attribute_value))
             background: {{ $headerBgColor->attribute_value }} !important;

            @else
             backgound:ornage;
        @endif
}

    </style>

    @yield('css')

</head>
<body>
<div class="wrapper">
   

    <div class="page-container"> 
        <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
            <![endif]-->
            <div class="logo-wrap">
                <div class="container-fluid" style="padding-bottom: 13px; padding-top: 20px;">
                    <div class="col-md-2 hide_logo"  style="text-align: center; display: none;">
                        <a href="{{URL::to('/')}}">

                            {{--institute profile checking--}}
                            <img src="{{URL::asset('assets/users/images/cadet-logo.png')}}"  class="brand-logo" alt="Alokito logo">
                           
                        </a>
                    </div>
                    @php    $campusName = getCampusProfile(); @endphp
                    <div class="col-md-5" id="inst_name" style="margin-bottom: 5px">
                        <div>
                            <h2 class="school-name">
                                Cadet College ERP
                            </h2>
                        </div>
                    </div>
                    <div class="col-md-4" id="brand_name">
                        <div style="text-align: center">
                            <h2 class="school-name">Cadet College SOP</h2>
                            <p>Knowledge, Morality, Patriotism</p>
                        </div>
                    </div>
                    {{--<div class="col-md-2"><h4 class="campus-name">@isset($campusName->name) {{getCampusProfile()->name}} @endisset</h4></div>--}}

                    @include('admin::layouts._header-custom-menu')

                </div><!--Logo wrap container End-->
            </div><!--Logo wrap End-->
            <!-- Add your site or application content here -->
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="{{URL::to('/')}}">

                        {{--institute profile checking--}}
                        <img src="{{URL::asset('assets/users/images/cadet-logo.png')}}"  class="brand-logo" alt="Alokito logo">

                    </a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    @include('admin::layouts._header-mega-menu-v2')
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
           

        <!-- Navbar -->
            <!-- header area start -->
            <div class="header-area" style="padding: 0px 30px;">
                <div class="row align-items-center">
                    <!-- nav and search button -->
                    <div class="col-md-4 col-sm-4 clearfix" style="display: flex;flex-wrap: nowrap;align-content: center;justify-content: flex-start; margin-top:5px;">
                        <div class="nav-btn pull-left d-inline">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <p style="color: #fff; margin-top:10px;" class="nav-name">Menu</p>
                        @if(in_array('levelofapproval/alert/notification', $menuAccess))
                            <ul class="pull-left" style="padding: 0; margin-left: 30px;">
                            
                                <li  style="padding-top: 10px;">
                                    <a href="{{url('/admin/bills/bill-info')}}" style="color:#70ec79;">
                                        <i class="fa fa-money" aria-hidden="true" style="margin-right: 10px;"></i>Bill
                                    </a>
                                </li>
                            
                            
                            </ul>
                        @endif
                       
                    </div>
                    <!-- profile info & task notification -->
                    >
                    <div class="col-md-8 clearfix">
                        <ul class="notification-area pull-right">
                            <li style="margin: 0; padding-top:10px;">
                            </li>
                            /* <li id="full-view"><i class="ti-fullscreen"></i></li>
                            <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
                          
                           
                            <li class="settings-btn">
                                <i class="ti-settings"></i>
                            </li> */
                        </ul>
                    </div>
                </div>
            </div>
            @yield('content')
          
        </div>
      
    </div>


    
</div>

<div class="push"></div>
<!--Warper End-->


{{--<footer class="footer">--}}
{{--<div class="container-fluid">--}}
{{--<div class="col-md-6">--}}
{{--<p class="copyright-footer-2">Alokito Software</p>--}}
{{--</div>--}}
{{--<div class="col-md-6">--}}
{{--<p class="copyright-footer">Copyright © 2017 Venus IT. All rights reserved.</p>--}}
{{--</div>--}}
{{--</div>--}}
{{--</footer>--}}


{{-- <script src='https://code.jquery.com/jquery-3.3.1.min.js'></script>--}}
<script src="{{URL::asset('js/all.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.validate.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>

<script src="{{URL::asset('template-2/js/vendor/modernizr-3.5.0.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/side-navbar.js') }}" type="text/javascript"></script>
<script src="{{URL::asset('template-2/js/main.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('template-2/js/jquery.smartmenus.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('template-2/js/jquery.smartmenus.bootstrap.js')}}" type="text/javascript"></script>



<script src="{{ URL::asset('js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>

<script src="{{URL::asset('js/jquery.cookie.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/bootstrap-waitingfor.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/datatable.js') }}" type="text/javascript"></script>
{{--<script type="text/javascript" src="https://cdn.datatables.net/s/dt/dt-1.10.10,se-1.1.0/datatables.min.js" ></script>--}}

<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="{{URL::asset('js/sweetalert.min.js') }}"></script>

{{--<script src="{{URL::asset('js/pic-chart.js')}}"></script>--}}

{{--<script src="{{URL::asset('js/pie-chartjs.js') }}"></script>--}}

<script>
    
$.sidebarMenu = function(menu) {
    var animationSpeed = 300;
    
    $(menu).on('click', 'li a', function(e) {
      var $this = $(this);
      var checkElement = $this.next();
  
      if (checkElement.is('.treeview-menu') && checkElement.is(':visible')) {
        checkElement.slideUp(animationSpeed, function() {
          checkElement.removeClass('menu-open');
        });
        checkElement.parent("li").removeClass("active");
      }
  
      //If the menu is not visible
      else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
        //Get the parent menu
        var parent = $this.parents('ul').first();
        //Close all open menus within the parent
        var ul = parent.find('ul:visible').slideUp(animationSpeed);
        //Remove the menu-open class from the parent
        ul.removeClass('menu-open');
        //Get the parent li
        var parent_li = $this.parent("li");
  
        //Open the target menu and add the menu-open class
        checkElement.slideDown(animationSpeed, function() {
          //Add the class active to the parent li
          checkElement.addClass('menu-open');
          parent.find('li.active').removeClass('active');
          parent_li.addClass('active');
        });
      }
      //if this isn't a link, prevent the page from being redirected
      if (checkElement.is('.treeview-menu')) {
        e.preventDefault();
      }
    });
  }
  
  $.sidebarMenu($('.sidebar_menu'))
</script>
@yield('scripts')
<!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
{{--<script>--}}
{{--window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;--}}
{{--ga('create','UA-XXXXX-Y','auto');ga('send','pageview')--}}
{{--</script>--}}
{{--<script src="https://www.google-analytics.com/analytics.js" async defer></script>--}}


<script>
    $(document).ready(function(){
        // ajax setup for laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>

<script>



    $(document).ready(function() {

        $(".dropdown-toggle-language").click(function(ev) {
            $("ul.menu-language").dropdown("toggle");
        });


        $(".dropdown-toggle-campus").click(function(ev) {
            $("ul.menu-campus").dropdown("toggle");
        });


//
//        $("ul.dropdown-menus").focusout(function() {
//            alert('sdafdsf');
//        });



        $('ul.menu-language >li > a:not(a[href="#"])').on('click', function() {
            self.location = $(this).attr('href');
        });

    });

    function startLoading(){
        $('.loading-screen').show();
    }
    function stopLoading(){
        $('.loading-screen').hide();  
    }
</script>




</body>
</html>
