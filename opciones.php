<?php 

require("classes/main.class.php");

session_start();

if ( !isset($_SESSION['userId']) || !isset($_SESSION['userGoogleDriveId']) )
	header( 'Location: index.php' ) ;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>e-Note Desarrollo</title>

	<link rel="stylesheet" href="css/960_16_col.css" />
	<link rel="stylesheet" href="css/estilos.css" />
	<link rel="stylesheet" href="css/reveal.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">

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
						<a href="agendas.php">Ver Agendas</a>
					</li>
					<li>
						<a href="index.php">Cerrar Sesion</a>
					</li>	
				</ul>
			</div>
		</div>



        <div id="ListaNotas" class="grid_16">
            <br><br>
            <br><br>
            <h1>Opciones</h1>
            <br><br>
            <ul>
            	<li><a href="descargar_xml.php">Exportar XML</a></li>
                <li>&nbsp;</li>
                <li>
                    <form action="importar_xml.php" method="post" enctype="multipart/form-data">
                    	<label for="file">Archivo Xml:</label>
                    	<input type="file" name="file" id="file">
                        <br>
                    	<input type="submit" name="Importar" value="Importar">
                    </form>
                </li>
            </ul>
        </div>
        
	</div>
</body>
</html>