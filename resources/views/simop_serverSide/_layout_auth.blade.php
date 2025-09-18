<!doctype html>
<html lang="en">

@include('simop_serverSide._layouts.auth.head')

<body class="bg-surface">

  <!--start wrapper-->
  <div class="wrapper">

        <!--start top header-->
            @include('simop_serverSide._layouts.auth.header')
        <!--end top header-->

        <!--start content-->
        @yield('content')
       <!--end page main-->



        <!--end footer-->
  </div>
  <!--end wrapper-->

  <!-- Bootstrap bundle JS -->
  <script src="{{asset('template_serverSide/assets/js/bootstrap.bundle.min.js')}}"></script>

  <!--plugins-->
  <script src="{{asset('template_serverSide/assets/js/jquery.min.js')}}"></script>
  <script src="{{asset('template_serverSide/assets/js/pace.min.js')}}"></script>

</body>

</html>