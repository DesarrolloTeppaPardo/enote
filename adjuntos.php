<?php 

require("classes/main.class.php");

session_start();

if ( !isset($_SESSION['userId']) || !isset($_SESSION['userGoogleDriveId']) )
	header( 'Location: index.php' ) ;
	
if ( !isset($_GET['agendaId']) || !isset($_GET['agendaId']) )
	header( 'Location: agendas.php' ) ;
	
if ( !isset($_GET['notaId']) && empty($_GET['notaId']) )
	header( 'Location: notas.php?agendaId'.$_GET['agendaId'] ) ;

$Nota = fabricaDao::getInstance()->createNotaDao()->getObject($_GET['notaId']);

if($Nota == NULL)
	header( 'Location: notas.php?agendaId'.$_GET['agendaId'] ) ;
	
$inicio = 0;

if (isset($_GET['pag']) && !empty($_GET['pag']) )
	$inicio = $_GET['pag'];
	
$oderby = NULL;
	
if (isset($_GET['oderby']) && !empty($_GET['oderby']))
	$oderby = $_GET['oderby'];

$desasc = 'desc';

if (isset($_GET['desasc']) && !empty($_GET['desasc']) && $_GET['desasc'] != 'desc')
	$desasc = 'asc';

$listAdjuntos = fabricaDao::getInstance()->createAdjuntoDao()->getList($_GET['notaId'],($inicio*10),$oderby,$desasc);

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
<script type="text/javascript" src="js/jquery.fineuploader-3.0.js"></script>
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
						<a id="<?php echo $_GET['notaId']; ?>" class="crearAdjunto">Crear Adjunto</a>
					</li>
                    <li>
						<a style="z-index:1000" href="crudNote.php?action=view&id=<?php echo $_GET['notaId']; ?>&agendaId=<?php echo $_GET['agendaId']; ?>">Ver Nota</a>
					</li>
					<li>
						<a style="z-index:1000" href="index.php">Cerrar Sesion</a>
					</li>	
				</ul>
			</div>
		</div>



		<div id="listAdjuntos" class="grid_16">
			<br><br>
			<div id="Botones" class="actions">
				<a class="Eliminar" id="eliminarAdjuntos" title="Eliminar">*</a>
			</div>
			<br><br>
			<h1>Listado de Adjuntos</h1>
			<br><br>

			

			<table width="945" class="NotasTodas">
				<tr>
                	<th width="34" scope="col">
                    	<input id="checkAll" name="checkAll" type="checkbox" value="">
                    </th>
					<th width="400" scope="col" style="cursor:pointer" class="orden"><a href="adjuntos.php?pag=<?php echo $inicio; ?>&oderby=nombre&desasc=<?php if ($desasc == 'desc') echo 'asc'; else echo'desc'; ?>">Notas</a></th>
					<th width="130" scope="col" style="cursor:pointer" class="orden"><a href="adjuntos.php?pag=<?php echo $inicio; ?>&oderby=fechaCreacion&desasc=<?php if ($desasc == 'desc') echo 'asc'; else echo'desc'; ?>">Fecha Creacion</a></th>
				</tr>

                <?php if($listAdjuntos['Adjuntos']) foreach ($listAdjuntos['Adjuntos'] as $Adjuntos) :?>
				<tr>
					<td align="center">
						<input class="checkEliminar" id="checkEliminar_<?php echo $Adjuntos['id']; ?>" type="checkbox" value="marca">
					</td>
					<td>
						<a target="_blank" title="descargar" class"current" href="<?php echo $Adjuntos['linkDescarga']; ?>"><?php echo $Adjuntos['titulo']; ?></a>
					</td>
					<td>
						<a target="_blank" title="descargar" class"current" href="<?php echo $Adjuntos['linkDescarga']; ?>"><?php echo $Adjuntos['fechaCreacion']; ?></a>
					</td>
				</tr>
                <?php endforeach;?>

			</table>
		</div>
        <div class="pagination pagination-right">
              <ul>
              <?php for ($i = 0; $i < $listAdjuntos['countPages']; $i++):?>
                <li><a href="adjuntos.php?agendaId=<?php echo $_GET['agendaId']; ?>&notaId=<?php echo $_GET['notaId']; ?>&pag=<?php echo $i; ?>"><?php echo ($i + 1); ?></a></li>
               <?php endfor;?>
              </ul>
            </div>

	</div>
</body>
</html>