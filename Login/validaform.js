
function controllaUsername() {
	var v = parseInt(document.myForm.username.value);
	if(!isNaN(v)){
		alert("L'user name non è un numero!");
		return false;
	}if(document.myForm.username.value == ""){
		alert("Hai lasciato il campo user name vuoto!");
		return false;
	}
	return true;
}
function controllaPassword() {
	var v = document.myForm.password.value;
	if(v.length < 10){
		alert("La password è lunga almeno 10 caratter alfanumerici!");
		return false;
	}
	if(v == "") {
		alert("Hai lasciato il campo Password vuoto!");
		return false;
	}
	return true;
}
function validaForm(){
	
	var ret = controllaUsername();
	if(!ret)
		return false;
	ret = controllaPassword();
	if(!ret)
		return false;
	alert("I dati sono stati inseriti correttamente!");
	if (document.myForm.remember.checked) {
        window.alert("Hai scelto di ricordarti per i prossimi accessi");
    }
    else {
        window.alert("Hai scelto di non ricordarti per i prossimi accessi");
    }
	return true;
}
