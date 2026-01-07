<!doctype html>

<html
  lang="en"
  class="light-style layout-wide"
  dir="ltr"
  data-theme="theme-default"
  data-template="vertical-menu-template-free"
  data-style="light">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Coming Soon - Pages</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/theme1/img/favicon/favicon.ico')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/theme1/vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/theme1/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/theme1/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/theme1/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/theme1/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/theme1/vendor/css/pages/page-misc.css')}}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/theme1/vendor/js/helpers.js')}}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/theme1/js/config.js')}}"></script>
  </head>

  <body>
    <!-- Content -->

    <!--Under Maintenance -->
    <div class="container-xxl container-p-y">
      <div class="misc-wrapper">
        <h3 class="mb-2 mx-2">Coming Soon! 🚧</h3>
        <p class="mb-6 mx-2">Sorry for the inconvenience, this feature is currently under development</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
        <div class="mt-6">
          <img
            src="{{ asset('assets/theme1/img/illustrations/girl-doing-yoga-light.png')}}"
            alt="girl-doing-yoga-light"
            width="500"
            class="img-fluid"
            data-app-light-img="illustrations/girl-doing-yoga-light.png"
            data-app-dark-img="illustrations/girl-doing-yoga-dark.png" />
        </div>
      </div>
    </div>
    <!-- /Under Maintenance -->


    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ asset('assets/theme1/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{ asset('assets/theme1/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{ asset('assets/theme1/vendor/js/bootstrap.js')}}"></script>
    <script src="{{ asset('assets/theme1/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{ asset('assets/theme1/vendor/js/menu.js')}}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('assets/theme1/js/main.js')}}"></script>

    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
