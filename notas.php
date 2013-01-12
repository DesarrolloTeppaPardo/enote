<?php 

require("classes/main.class.php");

session_start();

if ( !isset($_SESSION['userId']) || !isset($_SESSION['userGoogleDriveId']) )
	header( 'Location: index.php' ) ;
	
if ( !isset($_GET['agendaId']) && empty($_GET['agendaId']) )
	header( 'Location: agendas.php' ) ;

$Agenda = fabricaDao::getInstance()->createAgendaDao()->getObject($_GET['agendaId']);

if($Agenda == NULL)
	header( 'Location: agendas.php' );
	
$inicio = 0;

if (isset($_GET['pag']) && !empty($_GET['pag']) )
	$inicio = $_GET['pag'];
	
$oderby = NULL;
	
if (isset($_GET['oderby']) && !empty($_GET['oderby']))
	$oderby = $_GET['oderby'];

$desasc = 'desc';

if (isset($_GET['desasc']) && !empty($_GET['desasc']) && $_GET['desasc'] != 'desc')
	$desasc = 'asc';

$listaNotas = fabricaDao::getInstance()->createNotaDao()->getList($_GET['agendaId'],($inicio*10),$oderby,$desasc);

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
						<a href="crudAgenda.php?action=view&id=<?php echo $_GET['agendaId']; ?>">Ver Agenda</a>
					</li>
					<li>
						<a href="crudNote.php?action=create&agendaId=<?php echo $_GET['agendaId']; ?>">Crear Nota</a>
					</li>
					<li>
						<a href="index.php">Cerrar Sesion</a>
					</li>	
				</ul>
			</div>
		</div>



		<div id="ListaNotas" class="grid_16">
			<br><br>
			<div id="Botones" class="actions">
				<a href="#" class="Eliminar" id="eliminarNotas" title="Eliminar">*</a>
			</div>
			<br><br>
			<h1>Listado de Notas</h1>
			<br><br>

			

			<table width="945" class="NotasTodas">
				<tr>
                	<th width="34" scope="col">
                    	<input id="checkAll" name="checkAll" type="checkbox" value="">
                    </th>
					<th width="400" scope="col" style="cursor:pointer" class="orden"><a href="notas.php?pag=<?php echo $inicio; ?>&oderby=nombre&desasc=<?php if ($desasc == 'desc') echo 'asc'; else echo'desc'; ?>">Notas</a></th>
					<th width="130" scope="col" style="cursor:pointer" class="orden"><a href="notas.php?pag=<?php echo $inicio; ?>&oderby=fechaEdicion&desasc=<?php if ($desasc == 'desc') echo 'asc'; else echo'desc'; ?>">Fecha Editado</a></th>
					<th width="130" scope="col" style="cursor:pointer" class="orden"><a href="notas.php?pag=<?php echo $inicio; ?>&oderby=fechaCreacion&desasc=<?php if ($desasc == 'desc') echo 'asc'; else echo'desc'; ?>">Fecha Creacion</a></th>
				</tr>

                <?php if($listaNotas['Notas']) foreach ($listaNotas['Notas'] as $Notas) :?>
				<tr>
					<td align="center">
						<input class="checkEliminar" id="checkEliminar_<?php echo $Notas['id']; ?>" type="checkbox" value="marca">
					</td>
					<td>
						<a class"current" href="crudNote.php?agendaId=<?php echo $_GET['agendaId']; ?>&action=view&id=<?php echo $Notas['id']; ?>"><?php echo $Notas['titulo']; ?></a>
					</td>
					<td>
						<a class"current" href="crudNote.php?agendaId=<?php echo $_GET['agendaId']; ?>&action=view&id=<?php echo $Notas['id']; ?>"><?php echo $Notas['fechaEdicion']; ?></a>
					</td>
					<td>
						<a class"current" href="crudNote.php?agendaId=<?php echo $_GET['agendaId']; ?>&action=view&id=<?php echo $Notas['id']; ?>"><?php echo $Notas['fechaCreacion']; ?></a>
					</td>
				</tr>
                <?php endforeach;?>

			</table>
		</div>
        <div class="pagination pagination-right">
              <ul>
              <?php for ($i = 0; $i < $listaNotas['countPages']; $i++):?>
                <li><a href="notas.php?agendaId=<?php echo $_GET['agendaId']; ?>&pag=<?php echo $i; ?>"><?php echo ($i + 1); ?></a></li>
               <?php endfor;?>
              </ul>
            </div>

	</div>
</body>
</html>