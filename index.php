<?php 
require("classes/main.class.php");
session_start();
unset($_SESSION['userId']);
unset($_SESSION['userGoogleDriveId']);
unset($_SESSION['user']);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>e-Note Desarrollo</title>

<link rel="stylesheet" href="css/960_16_col.css" />
<link rel="stylesheet" href="css/estilos.css" />

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
    </div>
    <div id="slider" class="grid_16">
        <div class="slide_container">
            <div class="slide">
                <img src="img/slide1.jpg" alt="">
            </div>
        </div>
        <div class="img_over">
            <img src="img/borde_slider.png" alt="">
        </div>			
    </div>
    <div id="slogan" class="grid_16">Una clara manera de organizar tu agenda</div>
    <div id="content" class="grid_16">
        <div id="tres_columnas">
            <div class="grid_3col alpha">
                <div class="block_title">
                    <img src="img/1page_img1.png" alt="">
                    <div class="textos">
                        <div class="titulo">Usuarios Registrados</div>				
                    </div>
                </div>
                <div class="row">
                <form action="test.php" method="post" target="receiver">
                    <div class="campo">
                        <label for="nombre">Usuario</label>
                        <input type="text" name="usuario_login" maxlength="50" id="usuario_login">
                    </div>

                    <div class="campo">
                        <label for="clave">clave</label>
                        <input type="password" id="clave_login" maxlength="25" name="clave_login">		
                    </div>
 
                    <div class="actions">
                        <input type="submit" id="boton_sesion" value="Iniciar Sesion en e-Note">
                    </div>
                </form>
                <iframe frameborder="0" style="display:block; position:absolute; height:0; width:0;" name="receiver"></iframe>
                </div>				
            </div>
            
            <div class="grid_3col omega">
                <div class="block_title">
                    <img src="img/1page_img3.png" alt="">
                    <div class="textos">
                        <div class="titulo">Eres Nuevo?</div>
                        <div class="sub-titulo">Registrate</div>
                    </div>
                </div>
                <div class="row">
                <form action="" method="post">
                    <div class="campo">
                        <label for="nombre">Usuario</label>
                        <input type="text" name="usuario_registro" id="usuario_registro" maxlength="50">
                    </div>

                    <div class="campo">
                        <label for="clave">clave</label>
                        <input type="password" name="clave_registro" id="clave_registro" maxlength="25">		
                    </div>
                    
                    <div class="actions">
                        <input id="boton_registro" type="submit" value="Registrate en e-Note">
                        
                    </div>
                </form>
                </div>
            
            </div>
            <div class="grid_3col">
                <div class="block_title">
                    <img src="img/1page_img2.png" alt="">
                    <div class="textos">
                        <div class="titulo">Contacto</div>							
                    </div>
                </div>
                <p>
                    Pardo, Joana
                    <br/>
                    Cedula: 17587649
                    <br/>
                    Teppa, Juan
                    <br/>
                    Cedula: 18021327
                </p>
                
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
</body>
</html>