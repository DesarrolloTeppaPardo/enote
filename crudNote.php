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

if ( isset($_GET['action']) && !empty($_GET['action']) )
{
	$editable = false; 
	
	$listaEtiquetas = NULL;
	
	$MisEtiquetas = fabricaDao::getInstance()->createEtiquetaDao()->getMisEtiquetas($_SESSION['userId']);
	
	if ( $_GET['action'] == 'view')
	{
		if ( !isset($_GET['id']) || empty($_GET['id']) )
			header( 'Location: index.php' ) ;
			
		$Nota = fabricaDao::getInstance()->createNotaDao()->getObject($_GET['id']);
		
		$listaEtiquetas = fabricaDao::getInstance()->createEtiquetaDao()->getEtiquetasNota($_GET['id']);
		
		if ($Nota == NULL)
			header( 'Location: notas.php?agendaId='.$_GET['agendaId'] ) ;
	}
	else if ( $_GET['action'] == 'create')
		$editable = true;	
}
else
	header( 'Location: notas.php?agendaId='.$_GET['agendaId'] ) ;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>e-Note Desarrollo</title>

	<link rel="stylesheet" href="css/960_16_col.css" />
	<link rel="stylesheet" href="css/estilos.css" />
	<link rel="stylesheet" href="css/reveal.css">
    
    <link rel="stylesheet" type="text/css" href="js/css/ui-lightness/jquery-ui-1.9.2.custom.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.tagit.css">
    <link rel="stylesheet" type="text/css" href="css/tagit.ui-zendesk.css">
    
	<script src="js/js/jquery-1.8.3.js"></script>
    <script src="js/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="js/tag-it.js"></script>
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
						 <a href="notas.php?agendaId=<?php echo $_GET['agendaId']; ?>" id="">Ver Notas</a>
					</li>
					<li>
						<a href="index.php">Cerrar Sesion</a>
					</li>	
				</ul>
			</div>
		</div>

		<div id="ListaNotas" class="grid_16">
			
			<br><br>
			<h1>Manejo de Notas</h1>
			<br><br>
            <?php if (!$editable):?>
			<a href="<?php echo $Nota->getId(); ?>" id="editNote">Editar e-Note -- </a> 
			<a href="<?php echo $Nota->getId(); ?>" id="deleteNota">Eliminar e-Note</a> -- 
            <a href="adjuntos.php?agendaId=<?php echo $Agenda->getId(); ?>&notaId=<?php echo $_GET['id']; ?>" id="">Ver Adjuntos</a>
            <?php endif;?>
<div id="tres_columnas">
				<div class="grid_3col alpha">			
					<div class="row">

						
					<form action="" method="post">
						<div class="campoLargo">
                       <label style="width:520px;" for="fechas">Fecha Edicion: <?php if(!$editable) echo $Nota->getFechaEdicion(); ?> | Fecha Creacion: <?php if(!$editable) echo $Nota->getFechaCreacion(); ?></label>
                            <label for="titulo">Titulo</label>
							<input <?php  if(!$editable) echo 'disabled="disabled"'; ?> type="text" id="titulo" value="<?php if(!$editable) echo $Nota->getTitulo();?>" maxlength="100" nam="titulo">						
						</div>

						
						<div class="campoLargo">
							<label for="contenido">Contenido: </label>
				<textarea id="texto" name="contenido" <?php  if(!$editable) echo 'disabled="disabled"'; ?>><?php  if(!$editable) echo $Nota->getTexto();?></textarea>
						</div>
                        
                        <label for="notaEtiquetas">Etiquetas: </label>
                        <ul id="notaEtiquetas">
                        	<?php if ($listaEtiquetas != NULL) foreach ($listaEtiquetas['Etiquetas'] as $Etiqueta) :?>
							<li><?php echo $Etiqueta['nombre']; ?></li>
                            <?php endforeach;?>
                        </ul>
	
						<div class="actions">
                        <?php if ($editable):?>
							<input id="createNote" type="submit" value="Crear Nota" agendaId="<?php echo $_GET['agendaId'] ?>">
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

    <script type="text/javascript">
		$("#notaEtiquetas").tagit({
			availableTags: <?php echo $MisEtiquetas; ?>,
			allowSpaces : true,
			removeConfirmation : true,
			beforeTagRemoved: function(event, ui) 
			{
				// do something special
				var r = confirm("seguro que desea eliminar esta etiqueta?");
				if (!r)
					return false;
			},
			 
			afterTagAdded : function(event, ui) 
			{
				// do something special
				//$("#notaEtiquetas").tagit({readOnly : true});
				//readOnly : true
				//console.log($("#notaEtiquetas").tagit({readOnly : true}));
			}
		});
    </script>

</html>