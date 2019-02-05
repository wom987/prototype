<style type="text/css">
  .card-body{
    padding: 60px; 
    background-color: #2b2b28;
    border-radius: 6px;
  }
  .info{
    color: white;
    font-family: arial;
  }
  .coloured-icon{
    border: 1px solid white; 
    background-color: #2b2b28;
    box-shadow: 5px 5px 5px grey;
    cursor: pointer;
  }
  dt{
    font-size: 15px;
  }
  dd{
    font-size: 14px;
  }
</style>
<div class="col-md-12">
  <div class="card">
    <div class="card-body">        
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <div class="widget-small coloured-icon" id="catalogo"><span class="icon glyphicon glyphicon-book"></span>
            <div class="info"><br>
              <dl>
                <dt>CATÁLOGOS</dt>
                <dd>4</dd>
              </dl>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-6">
          <div class="widget-small coloured-icon" id="operaciones"><span class="icon glyphicon glyphicon-tasks"></span>
            <div class="info"><br>
              <dl>
                <dt>OPERACIONES</dt>
                <dd>2</dd>
              </dl>
            </div>
          </div>
        </div>        
      </div>  
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <div class="widget-small coloured-icon" id="reportes"><span class="icon glyphicon glyphicon-open-file"></span>
            <div class="info"><br>
              <dl>
                <dt>CONSULTAS Y REPORTES</dt>
                <dd>4</dd>
              </dl>
            </div>
          </div>
        </div>
        <?php if ($this->tank_auth->get_rol()==0) {?>
        <div class="col-md-6 col-lg-6" id="configuraciones">
          <div class="widget-small coloured-icon" id="seguridad" style="background-color: #dc3545;"><span class="icon glyphicon glyphicon-cog"></span>
            <div class="info"><br>
              <dl>
                <dt>CONFIGURACIONES</dt>
                <dd>2</dd>
              </dl>
            </div>
          </div>
        </div>
         <?php }?>
      </div> 
    </div>
  </div>
</div>   
   
    
    <!-- Google analytics script-->
    <script type="text/javascript">
      if(document.location.hostname == 'pratikborsadiya.in') {
      	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      	ga('create', 'UA-72504830-1', 'auto');
      	ga('send', 'pageview');
      }
        var url="<?php echo base_url(); ?>"; 
        //Redireccionamiento a distintas vistas
        /*catalogo.addEventListener('click', function (event) {
           location.href = "<?=base_url()?>catalogos/categoria";
        });*/
        $('body').on('click', '#catalogo', function(){
          location.href = url+"catalogos/categoria";
        }); 
        $('body').on('click', '#operaciones', function(){
          location.href = "<?=base_url()?>operaciones/grid_entradas" ;
        });
        $('body').on('click', '#reportes', function(){
          location.href = "<?=base_url()?>consultas/stock";
        });
        $('body').on('click', '#configuraciones', function(){
          location.href = "<?=base_url()?>configuraciones/grid_accesos";
        });
    </script>  
  </body>
</html>