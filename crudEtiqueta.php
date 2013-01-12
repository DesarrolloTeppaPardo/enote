<?php 

require("classes/main.class.php");

session_start();

if ( !isset($_SESSION['userId']) || !isset($_SESSION['userGoogleDriveId']) )
	header( 'Location: index.php' ) ;

if ( isset($_GET['action']) && !empty($_GET['action']) )
{	
	$editable = false; 
	
	if ( $_GET['action'] == 'view')
	{
		if ( !isset($_GET['id']) || empty($_GET['id']) )
			header( 'Location: index.php' ) ;
			
		$Etiqueta = fabricaDao::getInstance()->createEtiquetaDao()->getObject($_GET['id']);
		
		if ($Etiqueta == NULL)
			header( 'Location: agendas.php' ) ;
	}
	else if ( $_GET['action'] == 'create')
		$editable = true;	
}
else
	header( 'Location: agendas.php' ) ;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>e-Note Desarrollo</title>

	<link rel="stylesheet" href="css/960_16_col.css" />
	<link rel="stylesheet" href="css/estilos.css" />
	<link rel="stylesheet" href="css/reveal.css">
    
	<script type="text/javascript" src="js/jquery-1.8.2.min.js.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
    <script type="text/javascript" src="js/documentReady.js"></script>
    
</head>
<body>
<div class="velo" id="loader" style="display:none !important;">
    <div class="loader">
    </div>
</div>
	<div id="contenedor" class="container_16">
		<div id="header" class="grid_16">
			<div id="logo" class="grid_5 alpha">e-Note</div>

			<div id="menu" class="grid_11 omega">
				<ul>
					<li>
						<a class=""><?php echo $_SESSION['user']; ?></a>
					</li>
					
					 <li>
						<a href="etiquetas.php">Ver Etiquetas</a>
					</li>
					<li>
						<a href="index.php">Cerrar Sesion</a>
					</li>	
				</ul>
			</div>
		</div>

		<div id="ListaNotas" class="grid_16">
			
			<br><br>
			<h1>Manejo de Etiqueta</h1>
			<br><br>
            <?php if (!$editable):?>
			<a href="<?php echo $Etiqueta->getId(); ?>" id="editEtiqueta">Editar Etiqueta </a> -- 
			<a href="<?php echo $Etiqueta->getId(); ?>" id="deleteEtiqueta">Eliminar Etiqueta</a>
            <?php endif;?>
<div id="tres_columnas">
				<div class="grid_3col alpha">			
					<div class="row">

						
					<form action="" method="post">
						<div class="campoLargo">
							<label style="width:520px;" for="fechas">Fecha Edicion: <?php if(!$editable) echo $Etiqueta->getFechaEdicion(); ?> | Fecha Creacion: <?php if(!$editable) echo $Etiqueta->getFechaCreacion(); ?></label>
                            <label for="nombre">Nombre</label>
                           <p>&nbsp;</p>
							<input <?php  if(!$editable) echo 'disabled="disabled"'; ?> type="text" id="nombreEtiqueta" value="<?php if(!$editable) echo $Etiqueta->getNombre();?>" maxlength="100" nam="titulo">						
						</div>
                         <p>&nbsp;</p>
						<div class="actions">
                        <?php if ($editable):?>
							<input id="createEtiqueta" type="submit" value="Crear Etiqueta" >
                            <?php endif;?>
						</div>
					</form>
					</div>				
				</div>				
				<div class="clearfix"></div>
</div>		
		</div>


	</div>
</body>
</html>