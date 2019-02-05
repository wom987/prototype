<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php 
foreach($crud->css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<div style='height:20px;'></div>  
<!--<div style="padding: 10px">-->
	<?php echo $crud->output; ?>
<!--</div-->
<?php foreach($crud->js_files as $file): ?>
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<script type="text/javascript">
	$(document).ready(function ($) {        
       $("#field-de_telefono").mask('0000-0000');
    });
</script>
<script type="text/javascript" src="<?=base_url()?>js/jquery.mask.js"></script>