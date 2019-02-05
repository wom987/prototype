<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Usuario';
} else if ($login_by_username) {
	$login_label = 'Usuario';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?=base_url()?>assets/images/logo.jpg" />
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/template/docs/css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Alcaldía Municipal de Ilopango</title>    
  </head> 
<?php echo form_open($this->uri->uri_string()); ?>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <h1>Control de Inventario</h1>
      </div>
      <div class="login-box"> 
        <div class="login-form"> 
          <form>
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
            <div class="form-group">
              <label class="control-label"><?php echo form_label($login_label, $login['id']); ?></label>
              <input class="form-control" type="text" name="login" id="name" placeholder="Usuario" autofocus>  
              <div style="color: red;font-size: 13px;text-align: center;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></div>         
            </div>
            <div class="form-group">
              <label class="control-label"><?php echo form_label('Contraseña', $password['id']); ?></label>            
              <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña">
              <div style="color: red; font-size: 13px;text-align: center;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></div>
            </div>          
            <div class="form-group btn-container">            
              <button class="btn btn-primary btn-block" type="submit" name="submit" id="enter">
                <i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN
              </button>
            </div>
          </form>  
        </div>     
      </div>      
      	<!--<?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/', 'Register'); ?>-->
    </section>
<?php echo form_close(); ?>
    <!-- Essential javascripts for application to work-->
    <script src="<?=base_url()?>assets/template/docs/js/jquery-3.2.1.min.js"></script>
    <!-- Notify -->    
    <script type="text/javascript" src="<?=base_url()?>assets/template/docs/js/plugins/bootstrap-notify.min.js"></script>
    <script src="<?=base_url()?>assets/template/docs/js/popper.min.js"></script>
    <script src="<?=base_url()?>assets/template/docs/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>assets/template/docs/js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?=base_url()?>assets/template/docs/js/plugins/pace.min.js"></script>
    <script type="text/javascript">
      // Login Page Flipbox control
      $('.login-content [data-toggle="flip"]').click(function() {
      	$('.login-box').toggleClass('flipped');
      	return false;
      });
    </script>
  </body>
</html>
<script type="text/javascript">      
    $(document).ready(function() { 
    //Alerta  
    function alerta(mensaje,tipo='danger', icon='fa fa-exclamation-circle'){
      $.notify({                
        message: mensaje,
        icon: icon                
      },{
        type: tipo
      });
    }

    //Flash data
    <?php if($this->session->flashdata('msg')){ ?>
      alerta('<?php echo $this->session->flashdata('msg'); ?>');    
    <?php } ?>
    });
    </script> 