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
		document.getElementById("import-warning").innerHTML = "Importación finalizada";
	});
}

function alquileres(){
	document.getElementById("progress").setAttribute("aria-valuenow", "0");
	document.getElementById("progress").style.width = "0%";
	$('button').prop('disabled', true);
	fetch('http://estudiohenseldelavega.es/api/alquileres')
	.then(function(response) {
		document.getElementById("progress").setAttribute("aria-valuenow", "70");
		document.getElementById("progress").style.width = "70%";
		ventas();
	})
	.then(function(response) {
		console.log(response)
	});
}

function deleteAll(){
	fetch('http://estudiohenseldelavega.es/api/borrar')
	.then(function(response) {
		document.getElementById("progress").setAttribute("aria-valuenow", "30");
		document.getElementById("progress").style.width = "30%";
		alquileres();
	})
	.then(function(response) {
		console.log(response)
	});
}

