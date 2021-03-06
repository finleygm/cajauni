<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Tesoreria</title> 
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.css">
  
  <link rel="stylesheet" href="{{asset('css/app.css')}}">
  <link rel="stylesheet" href="{{ asset('js/chosen.css') }}"">  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
   <!--
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Inicio</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>
  -->
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
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

      
      

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt">rrro</i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large">exaple</i>
        </a>
      </li>
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
              <li class="user-header">
                <!--img src="/bower_components/admin-lte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"-->

                <p>
                {{Auth::user()->name}} -  {{Auth::user()->cargo}}
                  <small>Member since Nov. 2017</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">               
                {{ Auth::user()->name }}  <BR>
                {{strtoupper(Auth::user()->cargo)}}                 
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-left">
                  <a href="{{ route('usuario.cambiar_contrasenha',Auth::user()->id) }}" class="btn btn-default btn-flat">Cambiar Contrase??a</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat" 
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Cession</a>                  
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                </form>
              </li>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!--
    <a href="index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">UNIBOL GUARANI</span>
    </a>
    
    -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
        </div>
        <div class="info">
          <a href="#" class="d-block">UNIBOL</a>
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
              <li class="nav-item">
                <a href="{{route('cuenta_clasificador.index')}}" class="nav-link ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Clasificador de Cuentas</p>
                </a>
              </li>
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

          <li class="nav-item menu-open">
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
            </ul>
          </li> 

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
        <div class="row">         
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">          
          <section class="col-lg-7 connectedSortable">           
          </section>          
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">
          </section>
          <!-- right col -->
        </div>

        
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.1.0
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
<script src="{{asset('js/app.js')}}"></script>  <!-- aqui ya esta incluido el jquery por eso no lo incluyo-->
<script src="{{ asset('js/chosen.jquery.min.js') }}"></script>
@stack('scripts')
</body>
</html>
