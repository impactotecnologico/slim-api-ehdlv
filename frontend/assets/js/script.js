function ventas(){
	fetch('http://estudiohenseldelavega.es/api/ventas')
	.then(function(response) {
		document.getElementById("progress").setAttribute("aria-valuenow", "100");
		document.getElementById("progress").style.width = "100%";
		$('.toast').toast('show')
		$('button').prop('disabled', false);
	})
	.then(function(response) {
		document.getElementById("progress").style.width = "0%";
	});
}

function alquileres(){
	document.getElementById("progress").setAttribute("aria-valuenow", "0");
	document.getElementById("progress").style.width = "0%";
	$('button').prop('disabled', true);
	fetch('http://estudiohenseldelavega.es/api/alquileres')
	.then(function(response) {
		document.getElementById("progress").setAttribute("aria-valuenow", "50");
		document.getElementById("progress").style.width = "50%";
		ventas();
	})
	.then(function(response) {
		console.log(response)
	});
}

