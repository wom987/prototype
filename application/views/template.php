<!DOCTYPE html> 
<html lang="en">  
  <head>
    <link rel="shortcut icon" href="<?=base_url()?>assets/images/logo.jpg" />
    <meta name="description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
    <!-- Twitter meta-->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@pratikborsadiya">
    <meta property="twitter:creator" content="@pratikborsadiya">
    <!-- Open Graph Meta-->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Vali Admin">
    <meta property="og:title" content="Vali - Free Bootstrap 4 admin theme">    
    <meta property="og:description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
    <title>Alcaldía Municipal de Ilopango</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/template/docs/css/main.css">    
    <link href="<?=base_url()?>assets/template/docs/css/fontawesome/css/all.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/dataTables.bootstrap.min.css">
    <script src="<?=base_url()?>assets/template/docs/js/jquery-3.2.1.min.js"></script>
    <!-- Notify -->    
    <script type="text/javascript" src="<?=base_url()?>assets/template/docs/js/plugins/bootstrap-notify.min.js"></script>
    <!-- SweetAlert -->
    <script type="text/javascript" src="<?=base_url()?>assets/template/docs/js/plugins/sweetalert.min.js"></script>
    <script type="text/javascript"> 
    //Definiendo base url para usar en js
    var base_url="<?php echo base_url(); ?>";     
    //Alerta
      function alerta(mensaje,tipo='success', icon='fa fa-check'){
        $.notify({                
          message: mensaje,
          icon: icon                
        },{
          type: tipo
        });
      }
    $(document).ready(function() { 
      //Flash data
      <?php if($this->session->flashdata('msg')){ ?>
        alerta('<?php echo $this->session->flashdata('msg'); ?>');    
      <?php } ?>
    });
    </script>    
    <style type="text/css">
      .select2-selection__clear{
        font-size: 22px !important;
        padding-right: 10px;
        color: gray;
      }  
      div[data-notify="container"] { 
        width: auto; 
      }                 
    </style>   
  </head>

  <div style="width: 100%; height: 100%;overflow: hidden; overflow-y: hidden; overflow-x: hidden; z-index: 99999; position: fixed;" class="tile" id="wait">
    <div class="overlay">
      <div class="m-loader mr-4" style="width: 75px !important;">
        <svg class="m-circular" viewBox="25 25 50 50">
          <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"></circle>
        </svg>
      </div>
      <h3 class="l-text" style="font-size: 40px !important;">Loading</h3>
    </div>
  </div>

  <body class="app sidebar-mini rtl">
    <!-- Navbar-->
    <header class="app-header" style="position: inherit;">
      <?php 
      $metodo =  $this->router->method; 
      if ($metodo!='index') { ?>
      <a class="app-header__logo" href="<?=base_url('inicio/inicio')?>">Inventario</a>
      <?php } else {?>
        <p class="app-header__logo">Inventario</p>
      <?php } ?>
      <!-- Sidebar toggle button-->
      <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar">
        <span class="glyphicon glyphicon-menu-hamburger" style="font-size: 22px;margin-top: 14px;"></span>
      </a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">               
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <?php if ($this->tank_auth->get_rol()==0) {?>
            <li><a class="dropdown-item" href = "<?=base_url()?>configuraciones/grid_accesos"><i class="fa fa-cog fa-lg"></i> Configuraciones</a></li>
            <?php } ?>
            <li><a class="dropdown-item" id="cambiar" role="button"><i class="fa fa-key"></i> Cambiar clave</a></li>
            <li><a class="dropdown-item" href="<?=base_url().'auth/logout'?>"><i class="fa fa-undo fa-lg"></i> Salir</a></li>
          </ul>
        </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="<?=base_url()?>assets/images/avatar.png" alt="User Image">
        <div>
          <p class="app-sidebar__user-designation">Bienvenid@</p>
          <p class="app-sidebar__user-name">
            <?php if (strlen($this->tank_auth->get_username())>10){ 
              $user = substr($this->tank_auth->get_username(),0,8);
              echo $user." ...";
            } else{
              $user = $this->tank_auth->get_username();
              echo $user;
            }?>
          </p>
        </div>
      </div>
      <!-- CONTROLANDO LAS BARRAS DE MENÚ -->
      <ul class="app-menu">
        <?php
        //Carga según controlador actual, <a> active segun metodo         
        $controlador = $this->router->class;                      
        switch ($controlador) {
          case 'catalogos':            
          ?>
          <li><a class="app-menu__item <?= (($metodo =='categoria')?'active':'') ?>" href="<?=base_url('catalogos/categoria')?>"><i class="app-menu__icon fa fa-list-ul"></i><span class="app-menu__label">Categorías</span></a></li>
          <li><a class="app-menu__item <?= (($metodo =='producto')?'active':'') ?>" href="<?=base_url('catalogos/producto')?>"><i class="app-menu__icon fa fa-desktop"></i><span class="app-menu__label">Productos</span></a></li>
          <li><a class="app-menu__item <?= (($metodo =='departamento')?'active':'') ?>" href="<?=base_url('catalogos/departamento')?>"><i class="app-menu__icon fa fa-home"></i><span class="app-menu__label">Departamentos</span></a></li>          
          <li><a class="app-menu__item <?= (($metodo =='proveedor')?'active':'') ?>" href="<?=base_url('catalogos/proveedor')?>"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Proveedores</span></a></li>
          <?php                               
          break;
          case 'operaciones': 
          ?>
          <li><a class="app-menu__item <?= (($metodo =='grid_entradas' || $metodo =='entradas')?'active':'') ?>" href="<?=base_url('operaciones/grid_entradas')?>"><i class="app-menu__icon fa fa-cart-plus"></i><span class="app-menu__label">Entradas</span></a></li>              
          <li><a class="app-menu__item <?= (($metodo =='grid_salidas'|| $metodo =='salidas')?'active':'') ?>" href="<?=base_url('operaciones/grid_salidas')?>"><i class="app-menu__icon fa fa-cart-arrow-down"></i><span class="app-menu__label">Salidas</span></a></li>
          <?php            
          break;
          case 'consultas':           
          ?>
          <li><a class="app-menu__item <?= (($metodo =='stock')?'active':'') ?>" href="<?=base_url('consultas/stock')?>"><i class="app-menu__icon fa fa-archive"></i><span class="app-menu__label">Stock</span></a></li>
          <li><a class="app-menu__item <?= (($metodo =='entradas')?'active':'') ?>" href="<?=base_url('consultas/entradas')?>"><i class="app-menu__icon fa fa-cart-plus"></i><span class="app-menu__label">Entradas</span></a></li>
          <li><a class="app-menu__item <?= (($metodo =='salidas')?'active':'') ?>" href="<?=base_url('consultas/salidas')?>"><i class="app-menu__icon fa fa-cart-arrow-down"></i><span class="app-menu__label">Salidas</span></a></li>
          <li><a class="app-menu__item <?= (($metodo =='instalaciones')?'active':'') ?>" href="<?=base_url('consultas/instalaciones')?>"><i class="app-menu__icon fa fa-wrench"></i><span class="app-menu__label">Instalaciones</span></a></li>              
          <?php            
          break;
          case 'configuraciones':
          ?>
          <li><a class="app-menu__item <?= (($metodo =='grid_accesos')?'active':'') ?>" href="<?=base_url('configuraciones/grid_accesos')?>"><i class="app-menu__icon fa fa-user-secret"></i><span class="app-menu__label">Control de acceso</span></a></li>
          <li><a class="app-menu__item <?= (($metodo =='grid_usuarios')?'active':'') ?>" href="<?=base_url('configuraciones/grid_usuarios')?>"><i class="app-menu__icon fa fa-user-plus"></i><span class="app-menu__label">Usuarios</span></a></li>
          <?php
          break;
        } 
        ?>
      </ul>
    </aside>
    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-folder-open"></i> <?=$datos["titulo"]?></h1>          
        </div>
        <?php if ($metodo!='index') { ?>
          <ul class="app-breadcrumb breadcrumb">          
            <li class="breadcrumb-item"><a class="btn btn-secondary" style="background-color: #2b2b28;" href="<?=base_url('inicio/inicio')?>"><span class="glyphicon glyphicon-home"></span>&nbsp;/&nbsp;Inicio</a></li>
          </ul>
        <?php } ?> 
      </div>      
      <div class="row">
        <?php $this->load->view($vista, $datos);?> 
        <?php
          // print_r($_SESSION); 
          //PARA ACTUALIZAR ROL DE LA SESION AL EDITAR USUARIO       
          // $_SESSION['rol'] = 1;
        ?>          
      </div>
    </main>

    <!-- DataTables -->
    <script src="<?=base_url()?>assets/template/docs/datatables.net/js/jquery.dataTables.js"></script>
    <script src="<?=base_url()?>assets/template/docs/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>    
    <!-- Essential javascripts for application to work-->
    <script src="<?=base_url()?>assets/template/docs/js/popper.min.js"></script>
    <script src="<?=base_url()?>assets/template/docs/js/bootstrap.min.js"></script>    
    <!-- Select2 -->
    <script src="<?=base_url()?>assets/template/docs/js/select2.full.js"></script>

    <script src="<?=base_url()?>assets/template/docs/js/main.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/template/docs/js/plugins/bootstrap-datepicker.min.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?=base_url()?>assets/template/docs/js/plugins/pace.min.js"></script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="<?=base_url()?>assets/template/docs/js/plugins/chart.js"></script>    
    <script type="text/javascript">
      var data = {
        labels: ["January", "February", "March", "April", "May"],
        datasets: [
          {
            label: "My First dataset",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [65, 59, 80, 81, 56]
          },
          {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: [28, 48, 40, 19, 86]
          }
        ]
      };
      var pdata = [
        {
          value: 300,
          color: "#46BFBD",
          highlight: "#5AD3D1",
          label: "Complete"
        },
        {
          value: 50,
          color:"#F7464A",
          highlight: "#FF5A5E",
          label: "In-Progress"
        }
      ]      
    </script>
    <!-- Google analytics script-->
    <script type="text/javascript">
      $(document).ready(function(){
        dataTable();         
      })
      if(document.location.hostname == 'pratikborsadiya.in') {
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-72504830-1', 'auto');
        ga('send', 'pageview');
      }

      var url = "<?php echo base_url(); ?>";      
      // Crear datatable
      function dataTable(){
          $(".dataTables_wrapper .row").each(function(){
            $(this).addClass('col-md-12');
            $(this).find('.dataTables_length').css("float","left");
            $(this).find('.dataTables_length select').css("margin","8px");
            $(this).find('.dataTables_filter').css("float","right");
          })
        }
      // Aplicar select2
      $('select').select2();
      // Wait
      setTimeout(
          function(){
            $("#wait").hide();
            $(".app-header").css('position','fixed');
          }
        , 800
      );
      //Cambiar clave
      $('body').on('click', '#cambiar', function(){
        swal({
          title: "Cambio de credenciales",   
          text: "Ingrese su clave actual:",   
          type: "input",
          inputType: "password",
          showCancelButton: true,   
          closeOnConfirm: false,   
          animation: "slide-from-top",   
          inputPlaceholder: "Clave actual" 
        },
        function(inputValue){
          if (inputValue === false) return false;      
          if (inputValue === "") {
            swal.showInputError("Ingrese su clave actual");     
            return false;
          }

          $.ajax({
            url: url +'configuraciones/clave_actual',
            type: "POST",                    
            dataType: "html",
            data: { inputValue: inputValue },
            success: function (result) { 
            console.log(result);                           
              if (result==1) {
                swal({
                  title: "Cambio de credenciales",   
                  text: "Ingrese su nueva clave",   
                  type: "input",
                  inputType: "password",
                  showCancelButton: true,   
                  closeOnConfirm: false,   
                  animation: "slide-from-top",   
                  inputPlaceholder: "Nueva clave" 
                },
                function(inputValue){
                  if (inputValue === false) return false;      
                  if (inputValue === "") {     
                    swal.showInputError("Ingrese su nueva clave");     
                    return false;
                  }
                  if (inputValue.length <7 ) {     
                    swal.showInputError("La contraseña debe poseer un mínimo de 7 caracteres");     
                    return false;
                  }
                  $.ajax({
                    url: url +'configuraciones/change_pasword',
                    type: "POST",                    
                    dataType: "html",
                    data: { inputValue: inputValue },
                    success: function () {                            
                      if (result==1) {
                        swal("¡Listo!", "Su clave ha sido modificada");
                        window.setTimeout(function() {;
                              window.location = base_url+'auth/logout';
                           }, 2000);
                      }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                      swal("Error!", "Intente de nuevo", "error");
                    }
                  });                      
                });
              } else{
                swal.showInputError("La contraseña ingresada no es su clave actual");     
                return false;
              }
            },
            error: function (xhr, ajaxOptions, thrownError) {
              swal("Error!", "Intente de nuevo", "error");
            }
          });
        });           
      });
    </script>
  </body>
</html>