function sesionRegistrovalidate(usuario,clave,accion)
{
	$("#loader").show();
	$.ajax({
		url : "ajax/validate.php",
		type : "POST",
		dataType : "json",
		data : {'usuario' : usuario, 'clave' : clave, 'accion' : accion},
		success  : function(data){
			//console.log(data);
			if (data.ok == 1)
				window.location = 'agendas.php';
			else
				$("#loader").fadeOut("slow",function(){alert(data.error);});
		},
		error : function () {console.log("Error sesionRegistrovalidate");},
	});
}

function createAgenda ()
{
	$("#loader").show();
	$.ajax({
		url : "ajax/createAgenda.php",
		type : "POST",
		dataType : "json",
		data : {'nombre' : $('#nombreAgenda').val()},
		success  : function(data)
		{
			var mensaje;
			
			if (data.ok == 1)
				mensaje = 'Su agenda se ha creado exitosamente';
			else
				mensaje = data.error;
				
			$("#loader").fadeOut("slow",function(){
				$("#nombreAgenda").val('');
				alert(mensaje);
			});
		},
		error : function () {console.log("Error createAgenda");},
	});
}

function createEtiqueta ()
{
	$("#loader").show();
	$.ajax({
		url : "ajax/createEtiqueta.php",
		type : "POST",
		dataType : "json",
		data : {'nombre' : $('#nombreEtiqueta').val()},
		success  : function(data)
		{
			var mensaje;
			
			if (data.ok == 1)
				mensaje = 'Su etiqueta se ha creado exitosamente';
			else
				mensaje = data.error;
				
			$("#loader").fadeOut("slow",function(){
				$("#nombreEtiqueta").val('');
				alert(mensaje);
			});
		},
		error : function () {console.log("Error createEtiqueta");},
	});
}

function createNote (agendaId)
{
	$("#loader").show();
	$.ajax({
		url : "ajax/createNote.php",
		type : "POST",
		dataType : "json",
		data : {'titulo' : $('#titulo').val(), 'texto' : $('#texto').val(), 'agendaId' : agendaId, 'etiquetas' : $("#notaEtiquetas").tagit("assignedTags")},
		success  : function(data)
		{
			var mensaje;
			
			if (data.ok == 1)
				mensaje = 'Su Nota se ha creado exitosamente';
			else
				mensaje = data.error;
				
			$("#loader").fadeOut("slow",function(){
				$("#titulo").val('');
				$("#texto").val('');
				$("#notaEtiquetas").tagit("removeAll");
				alert(mensaje);
			});
		},
		error : function () {console.log("Error createNote");},
	});
}

function updateAgenda ()
{
	$("#loader").show();
	$.ajax({
		url : "ajax/updateAgenda.php",
		type : "POST",
		dataType : "json",
		data : {'nombre' : $('#nombreAgenda').val(),'id' : $('#editAgenda').attr('href')},
		success  : function(data)
		{
			//console.log(data);
			var mensaje;
			
			if (data.ok == 1)
				mensaje = 'La agenda fue editada exitosamente';
			else
				mensaje = data.error;
				
			$("#loader").fadeOut("slow",function(){
				alert(mensaje);
				document.location.reload(true);
			});
		},
		error : function () {console.log("Error updateAgenda");},
	});
}

function updateEtiqueta ()
{
	$("#loader").show();
	$.ajax({
		url : "ajax/updateEtiqueta.php",
		type : "POST",
		dataType : "json",
		data : {'nombre' : $('#nombreEtiqueta').val(),'id' : $('#editEtiqueta').attr('href')},
		success  : function(data)
		{
			//console.log(data);
			var mensaje;
			
			if (data.ok == 1)
				mensaje = 'La etiqueta fue editada exitosamente';
			else
				mensaje = data.error;
				
			$("#loader").fadeOut("slow",function(){
				alert(mensaje);
				document.location.reload(true);
			});
		},
		error : function () {console.log("Error updateEtiqueta");},
	});
}


function updateNote ()
{
	$("#loader").show();
	$.ajax({
		url : "ajax/updateNote.php",
		type : "POST",
		dataType : "json",
		data : {
			'titulo' : $('#titulo').val(), 
			'texto' : $('#texto').val(), 
			'id' : $('#editNote').attr('href'), 
			'etiquetas' : $("#notaEtiquetas").tagit("assignedTags")
		},
		success  : function(data)
		{
			//console.log(data);
			var mensaje;
			
			if (data.ok == 1)
				mensaje = 'La nota fue editada exitosamente';
			else
				mensaje = data.error;
				
			$("#loader").fadeOut("slow",function(){
				alert(mensaje);
				document.location.reload(true);
			});
		},
		error : function () {console.log("Error updateNote");},
	});
}

function getIdsToDelete(element)
{
	var values = new Array();
	
	$.each($(".checkEliminar"), function() 
	{
		if($(this).is(':checked'))
		{
			var aux = $(this).attr('id').split('checkEliminar_')
			values.push(aux[1]);
		}
	});
	
	if(element.attr('id') == 'deleteAgenda' || element.attr('id') == 'deleteEtiqueta' || element.attr('id') == 'deleteNota' || element.attr('id') == 'eliminarAdjuntos' )
		values.push(element.attr('href'));
	
	if (values.length == 0)
		alert("Debe seleccionar al menos un item");
	else
	{
		var r = confirm("Desea eliminar esto(s) items(s)?");
		if (r == true)
			return values;
		else
			return false;
	}
}

function deleteAgendas(values)
{
	$("#loader").show();
	$.ajax({
		url : "ajax/deleteAgendas.php",
		type : "POST",
		dataType : "json",
		data : {'values' :values},
		success  : function(data)
		{
			var mensaje;
			
			if (data.ok == 1)
				mensaje = 'La agenda fue eliminada exitosamente';
			else
				mensaje = data.error;
				
			$("#loader").fadeOut("slow",function(){
				alert(mensaje);
				window.location = "agendas.php";
			});
		},
		error : function () {console.log("Error deleteAgendas");},
	});
}

function deleteEtiquetas(values)
{
	$("#loader").show();
	$.ajax({
		url : "ajax/deleteEtiquetas.php",
		type : "POST",
		dataType : "json",
		data : {'values' :values},
		success  : function(data)
		{
			var mensaje;
			
			if (data.ok == 1)
				mensaje = 'La etiqueta fue eliminada exitosamente';
			else
				mensaje = data.error;
				
			$("#loader").fadeOut("slow",function(){
				alert(mensaje);
				window.location = "etiquetas.php";
			});
		},
		error : function () {console.log("Error deleteEtiquetas");},
	});
}

function deleteNotas (values)
{
	$("#loader").show();
	$.ajax({
		url : "ajax/deleteNotas.php",
		type : "POST",
		dataType : "json",
		data : {'values' :values},
		success  : function(data)
		{
			var mensaje;
			
			if (data.ok == 1)
				mensaje = 'La nota fue eliminada exitosamente';
			else
				mensaje = data.error;
				
			$("#loader").fadeOut("slow",function(){
				alert(mensaje);
				document.location.reload(true);
			});
		},
		error : function () {console.log("Error deleteNotas");},
	});
}

function deleteAdjuntos (values)
{
	$("#loader").show();
	$.ajax({
		url : "ajax/deleteAdjuntos.php",
		type : "POST",
		dataType : "json",
		data : {'values' :values},
		success  : function(data)
		{
			var mensaje;
			
			if (data.ok == 1)
				mensaje = 'Los adjuntos fue eliminados exitosamente';
			else
				mensaje = data.error;
				
			$("#loader").fadeOut("slow",function(){
				alert(mensaje);
				document.location.reload(true);
			});
		},
		error : function () {console.log("Error deleteAdjuntos");},
	});
}