<!doctype html>
<html lang="en">

@include('simop_serverSide._layouts.head')

<body>


  <!--start wrapper-->
  <div class="wrapper">
    <!--start top header-->

      @include('simop_serverSide._layouts.header')
       <!--end top header-->

       <!--start sidebar -->
        <aside class="sidebar-wrapper" data-simplebar="true">
          <div class="sidebar-header">
            <div>
              <img src="{{asset('template_serverSide/assets/images/logo-icon.png')}}" class="logo-icon" alt="logo icon">
            </div>
            <div>
              <img class="logo-text" src="{{asset('template_serverSide/assets/images/logo-text.png')}}" class="logo-icon" alt="logo text">
            </div>
            <div class="toggle-icon ms-auto"> <i class="bi bi-list"></i>
            </div>
          </div>
          <!--navigation-->
          @include('simop_serverSide._layouts.aside')
          <!--end navigation-->
       </aside>
       <!--end sidebar -->

       <!--start content-->
       @yield('content')
       <!--end page main-->


       <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
       <!--end overlay-->

       <!--start footer-->
        @include('simop_serverSide._layouts.footer')
        <!--end footer-->


        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <!--start switcher-->
       <div class="switcher-body">
        <button class="btn btn-primary btn-switcher shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling"><i class="bi bi-paint-bucket me-0"></i></button>
        <div class="offcanvas offcanvas-end shadow border-start-0 p-2" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling">
          <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Theme Customizer</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
          </div>
          <div class="offcanvas-body">
            <h6 class="mb-0">Theme Variation</h6>
            <hr>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="LightTheme" value="option1">
              <label class="form-check-label" for="LightTheme">Light</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="DarkTheme" value="option2">
              <label class="form-check-label" for="DarkTheme">Dark</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="SemiDarkTheme" value="option3">
              <label class="form-check-label" for="SemiDarkTheme">Semi Dark</label>
            </div>
            <hr>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="MinimalTheme" value="option3" checked>
              <label class="form-check-label" for="MinimalTheme">Minimal Theme</label>
            </div>
            <hr/>
            <h6 class="mb-0">Header Colors</h6>
            <hr/>
            <div class="header-colors-indigators">
              <div class="row row-cols-auto g-3">
                <div class="col">
                  <div class="indigator headercolor1" id="headercolor1"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor2" id="headercolor2"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor3" id="headercolor3"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor4" id="headercolor4"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor5" id="headercolor5"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor6" id="headercolor6"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor7" id="headercolor7"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor8" id="headercolor8"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
       </div>
       <!--end switcher-->

  </div>
  <!--end wrapper-->

  <!-- Bootstrap bundle JS -->
  <script src="{{asset('template_serverSide/assets/js/bootstrap.bundle.min.js')}}"></script>
  <!--plugins-->
  <script src="{{asset('template_serverSide/assets/js/jquery.min.js')}}"></script>

  <script src="{{asset('template_serverSide/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
  <script src="{{asset('template_serverSide/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>

  {{-- <script src="{{asset('template_serverSide/assets/plugins/easyPieChart/jquery.easypiechart.js')}}"></script> --}}

  <script src="{{asset('template_serverSide/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>

  <script src="{{asset('template_serverSide/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('template_serverSide/assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
  <script src="{{asset('template_serverSide/assets/js/table-datatable.js')}}"></script>

  <script src="{{asset('template_serverSide/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
  <script src="{{asset('template_serverSide/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
  <script src="{{asset('template_serverSide/assets/js/pace.min.js')}}"></script>

  <script src="{{asset('template_serverSide/assets/plugins/chartjs/js/Chart.min.js')}}"></script>
  <script src="{{asset('template_serverSide/assets/plugins/chartjs/js/Chart.extension.js')}}"></script>
  <script src="{{asset('template_serverSide/assets/plugins/apexcharts-bundle/js/apexcharts.min.js')}}"></script>
  {{-- <script src="{{asset('template_serverSide/assets/js/data-widgets.js')}}"></script> --}}
  <!--app-->
  <script src="{{asset('template_serverSide/assets/js/app.js')}}"></script>
  <script src="{{asset('template_serverSide/assets/js/app-chat-box.js')}}"></script>
  <script src="{{asset('template_serverSide/assets/js/index.js')}}"></script>

  <script>
      new PerfectScrollbar(".best-product")
  </script>

  @yield('scripts')
</body>

</html>




