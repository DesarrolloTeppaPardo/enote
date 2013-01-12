$(document).ready(function(){
	
	$('#boton_sesion').click(function(){
		sesionRegistrovalidate($('#usuario_login').val(),$('#clave_login').val(),'sesion');
		return false;
	});
	
	$('#boton_registro').click(function(){
		sesionRegistrovalidate($('#usuario_registro').val(),$('#clave_registro').val(),'registro');
		return false;
	});
	
	$('#checkAll').click(function(){
		
		if ($(this).is(':checked'))
			$("input:checkbox").attr("checked", true);
		else
			$("input:checkbox").attr("checked", false);
	});
	
	$('#eliminarEtiquetas, #deleteEtiqueta').click(function()
	{
		var values = getIdsToDelete($(this));
		if(values)
			deleteEtiquetas(values);
		 
		return false;
	});
	
	$('#eliminarAgendas, #deleteAgenda').click(function()
	{
		var values = getIdsToDelete($(this));
		if(values)
			deleteAgendas(values);
		 
		return false;
	});
	
	$('#eliminarNotas, #deleteNota').click(function()
	{
		var values = getIdsToDelete($(this)); 
		if(values)
			deleteNotas(values);
		 
		return false;
	});
	
	$('#eliminarAdjuntos').click(function()
	{
		var values = getIdsToDelete($(this)); 
		if(values)
			deleteAdjuntos(values);
		 
		return false;
	});
	
	
	
	$('#createAgenda').click(function(){
		createAgenda();
		return false;
	});
	
	$('#createEtiqueta').click(function(){
		createEtiqueta();
		return false;
	});
	
	$('#createNote').click(function(){
		createNote($(this).attr('agendaId'));
		return false;
	});
	
	$('#editNote').click(function(){
		
		if($("#updateNote").length > 0)
		{
			$('#titulo').attr('disabled','disabled');
			$('#texto').attr('disabled','disabled');
			$('.actions').html('');
		}
		else
		{
			$('#titulo').removeAttr('disabled');
			$('#texto').removeAttr('disabled');
			$('.actions').html('<input id="updateNote" type="submit" value="Guardar Nota">');
		}
		return false;
	});
	
	$('#editAgenda').click(function(){
		
		if($("#updateAgenda").length > 0)
		{
			$('#nombreAgenda').attr('disabled','disabled');
			$('.actions').html('');
		}
		else
		{
			$('#nombreAgenda').removeAttr('disabled');
			$('.actions').html('<input id="updateAgenda" type="submit" value="Guardar Agenda">');
		}
		return false;
	});
	
	$('#editEtiqueta').click(function(){
		
		if($("#updateEtiqueta").length > 0)
		{
			$('#nombreEtiqueta').attr('disabled','disabled');
			$('.actions').html('');
		}
		else
		{
			$('#nombreEtiqueta').removeAttr('disabled');
			$('.actions').html('<input id="updateEtiqueta" type="submit" value="Guardar Etiqueta">');
		}
		return false;
	});
	
	$('#updateAgenda').live('click',function(){
		updateAgenda();
		return false;
	});
	
	$('#updateEtiqueta').live('click',function(){
		updateEtiqueta();
		return false;
	});
	
	$('#updateNote').live('click',function(){
		updateNote();
		return false;
	});
	
	var uploader = new qq.FineUploaderBasic
	({
		button: $('.crearAdjunto')[0],
		request: { endpoint: 'ajax/createAdjunto.php?notaId='+$('.crearAdjunto').attr('id')},
		//validation: { allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],	sizeLimit: 1024*1024*7 },
		autoUpload: true,
		multiple: false,
		callbacks: 
		{
			onSubmit: function(id, fileName) 
			{
				
			},
			onUpload: function(id, fileName) 
			{
				$("#loader").show();
			},
			onProgress: function(id, fileName, loaded, total) 
			{
			},
			onComplete: function(id, fileName, responseJSON) 
			{	console.log(responseJSON);
				$("#loader").hide();	
				alert("El adjunto se subio exitosamente");
				document.location.reload(true);
			},
			onError: function(id, fileName, reason)
			{
				$("#loader").hide();	
				alert(reason);
			}
		},
      debug: false
	});
	
});