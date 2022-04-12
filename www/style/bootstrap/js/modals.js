submitModal = function(id){
	$('#myModal' + id).modal('show');
	document.forms['form' + id].submit();

}