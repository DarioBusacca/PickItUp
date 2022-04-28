
function controllaNome(){
	var v = parseInt(document.myForm.nome.value);
	if(!isNaN(v)) {
		alert("Il nome non può essere un numero!");
		return false;
	}
	if(document.myForm.nome.value == ""){
		alert("Non hai inserito il nome!");
		return false;
	}
	return true;
}
function controllaUsername(){
	var v = parseInt(document.myForm.username.value);
	if(!isNaN(v)) {
		alert("L'username non può essere un numero!");
		return false;
	}
	if(document.myForm.username.value == ""){
		alert("Non hai inserito l'username!");
		return false;
	}
	return true;
}
function controllaCognome(){
	var v = parseInt(document.myForm.cognome.value);
	if(!isNaN(v)) {
		alert("Il cognome non può essere un numero");
		return false;
	}
	if(document.myForm.cognome.value == ""){
		alert("Non hai inserito il cognome!");
		return false;
	}
	return true;
}
function controllaEmail(){
	var v = parseInt(document.myForm.email.value);
	if(!isNaN(v)) {
		alert("La email non può essere un numero!");
		return false;
	}
	if(document.myForm.email.value == ""){
		alert("Non hai inserito l'email!");
		return false;
	}
	return true;
}
function controllaPassword(){
	var v = document.myForm.password.value;
	if(v.length < 10){
		alert("La password deve essere lunga almeno 10 caratteri alfanumerici!");
		return false;
	}
	if(v == ""){
		alert("Non hai inserito la password!");
		return false;
	}
	
	return true;
}
function validaForm(){
	var ret = controllaNome();
	if(!ret)
		return false;
	ret =controllaCognome();
	if(!ret)
		return false;
	ret = controllaUsername();
	if(!ret)
		return false;
	ret = controllaEmail();
	if(!ret)
		return false;
	ret = controllaPassword();
	if(!ret)
		return false;
	alert("I dati sono stati inseriti correttamente!");
	return true;
}