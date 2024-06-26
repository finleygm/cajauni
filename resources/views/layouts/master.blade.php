<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Tesoreria/Comercializacion</title>
  <link rel="icon" href="{{asset('/storage/uploads/icon19.png')}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.css">

  <link rel="stylesheet" href="{{asset('css/app.css')}}">
  <link rel="stylesheet" href="{{ asset('js/chosen.css') }}"">  
  <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
   <style>
    

    .print{
      display: none;
    }

    @media print
    { 
      
      
        .no-print, .no-print *
        {
            display: none !important;
        }
        
        /* 
        .print-left{
          display: inline !important;
            text-align: start !important;
            background-color: yellow !important;
            border: 1px solid;
        }
      .print-right{
        display: inline !important;
            text-align: right !important;
            background-color: aqua !important;
            border: 1px solid; width: 60%; */
       }
    /* }
    @media screen {
      .print-left{
            display: none !important;
        }
      .print-right{
            display: none !important;
       }
    } */
   </style>
</head>
<body class=" hold-transition sidebar-mini layout-fixed">


  <div class="wrapper">

    <!--
Preloader 
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

 Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->

      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <!--<li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('home') }}" class="nav-link">Inicio</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->

        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" style="margin-left: 800px;" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search 
        <li class="nav-item">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li>
-->

        <!--    <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large">exaple</i>

          </a>

        </li>  -->
        <li class="dropdown user user-menu">
          <!-- Menu Toggle Button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!-- The user image in the navbar-->
            <!--img src="/bower_components/admin-lte/dist/img/user2-160x160.jpg" class="user-image" alt="User Image"-->
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span class="hidden-xs">{{ Auth::user()->name }}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- The user image in the menu -->
            <li class="user-header" style="text-align: center;">

              @if (strtoupper(Auth::user()->sexo)=='MASCULINO')
              <img src="{{ asset('/storage/uploads/hombre.png') }}" style="width: 50px " class="img-circle elevation-2" alt="User Image">
              @else
              <img src="{{ asset('/storage/uploads/mujer.png') }}" style="width: 50px " class="img-circle elevation-2" alt="User Image">

              @endif
              <br>

              <div class="container" style="justify-content: center;">
             
              <div class="box" style="font-size: large;">
                
              
                <p>
                   {{strtoupper(Auth::user()->cargo)}}
                    <br>
                     {{Auth::user()->name}}
                    <br>
                    @php
                    $date = date('d-M-Y');
                    @endphp
                    <small>
                      @php
                      echo $date;
                      @endphp</small> <br>
                  <br>
                </p> 
               </div>
              </div>
              <br>

            </li>
            <br>
            <!-- Menu Body -->
            <li class="user-body">
              <div class="container">
                <!-- Content here -->
                <div class="row">
                <strong>Correo: </strong>
                  {{(Auth::user()->email)}}
                </div>
              </div>


              <!-- /.row -->
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
              <!--  <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div> -->
              <div class="pull-left">
                <a href="{{ route('usuario.cambiar_contrasenha',Auth::user()->id) }}" class="btn btn-default btn-flat">Cambiar Contraseña</a>
              </div>
              <div class="pull-right">
                <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Cession</a>
              </div>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </li>
          </ul>

        </li>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-secundary elevation-4" >
      <!-- Brand Logo -->

      <a href="{{ asset('/storage/uploads/unnamed.jpg') }}" class="brand-link">
        <!--  <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
        <img src="{{ asset('/storage/uploads/unnamed.jpg') }}" width="50" class="img-circle elevation-2" alt="User Image">
        <span class="brand-text font-weight-light">UNIBOL GUARANI</span>
      </a>


      <!-- Sidebar -->
      <div class="sidebar-dark">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="{{ asset('/storage/uploads/icon16.png')}}" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="{{ route('home') }}" class="d-block" style="color:aquamarine;">
              <span class="brand-text font-weight-light">INICIO</span>
            </a>

          </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-database"></i>
                <p>
                  CUENTAS Y CLIENTES
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('rubro.index')}}" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Rubros</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('unidad.index')}}" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Unidades</p>
                  </a>
                </li>
              <!--   -->
                <li class="nav-item">
                  <a href="{{route('cuenta.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Cuenta</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('cliente.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Cliente</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cash-register"></i>
                <p>
                  CAJA
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('pago.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listar de Pagos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('pago.create')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Realizar Cobros</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item ">
              <a href="#" class="nav-link ">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>
                  REPORTES
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('reportes')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Generar Reportes</p>
                  </a>
                </li>
              <!--   <li class="nav-item">
                <a href="{{route('reportes.reportes_rubro')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Consolidado por Rubro</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('reportes.reportes_clasificador')}}" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Consolidado por Clasificador</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('reportes.reportes_extracto')}}" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Extracto</p>
                  </a>
                </li>
            -->

              </ul>
            </li>

            @if ((Auth::user()->cargo)=='Administrador')

            <li class="nav-item ">
              <a href="#" class="nav-link ">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  USUARIOS
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('usuario.index')}}" class="nav-link">
                    <i class="fas fa-user-circle"></i>
                    <p>Listar Usuario</p>
                  </a>
                </li>
              </ul>
            </li>
            @endif

            @if ((Auth::user()->categoria)=='2')
            <li class="nav-item ">
              <a href="#" class="nav-link ">
              <i class="nav-icon  fa fa-book" ></i>
                <p>
                  CLASIFICADOR DE CUENTAS - PRODUCTOS
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
              <li class="nav-item">
                  <a href="{{route('cuenta_prod_clasificador.index')}}" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Clasificador de Cuentas</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('cuenta_prod_clasificador.create')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Realizar Clasificador de cuentas</p>
                  </a>
                </li>
              </ul>
            </li>
            @endif
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->

      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid" id="panel_principal">
          @yield('contenido')
          <!-- Small boxes (Stat box) -->
          <!-- <div class="row" >         
    

        <img src="{{ asset('/storage/uploads/unibol.png') }}" width="500"  alt="User Image"-->

          <!-- /.row -</div>-->
          <!-- Main row -->
          <!-- <div class="row">          
          <section class="col-lg-7 connectedSortable">           
          </section>     
          <img src="{{ asset('/storage/uploads/unibol.png') }}" width="500"  alt="User Image"> -->

          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <!--     <section class="col-lg-5 connectedSortable">
          </section -->
          <!-- right col 
        </div>-->
          <div class="container-fluid">

            <!-- Content here -->

            <!--img src="{{ asset('/storage/uploads/cajc3.jpg') }}" class="rounded mx-auto d-block" alt="User Image"-->
          </div>

          <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer no-print">
      <strong >Copyright &copy; 2023 <a href="https://unibolguarani.edu.bo/">UNIBOL-AT</a>.</strong>
      Todos los derechos reservados
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0
      </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  <script>
    //$.widget.bridge('uibutton', $.ui.button);
  </script>
  <script src="{{asset('js/app.js')}}?ver=2"></script> <!-- aqui ya esta incluido el jquery por eso no lo incluyo-->
  <script src="{{ asset('js/chosen.jquery.min.js') }}"></script>
  @stack('scripts')
  </body>

</html>