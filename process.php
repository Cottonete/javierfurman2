<?php
if( isset($_POST) ){
	
	//form validation vars
	$formok = true;
	$errors = array();
	
	//sumbission data
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	$date = date('d/m/Y');
	$time = date('H:i:s');
	
	//form data
	$name = $_POST['name'];	
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$enquiry = $_POST['enquiry'];
	
	//validate form data
	
	//validate name is not empty
	if(empty($name)){
		$formok = false;
		$errors[] = "Por favor ingrese un nombre y apellido";
	}
	
	//validate email address is not empty
	if(empty($email)){
		$formok = false;
		$errors[] = "Por favor ingrese una dirección de email";
	//validate email address is valid
	}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$formok = false;
		$errors[] = "Esa no es una dirección de email válida";
	}
	
	//validate message is not empty
	if(empty($message)){
		$formok = false;
		$errors[] = "Por favor ingrese el motivo de consulta";
	}
	//validate message is greater than 20 charcters
	// elseif(strlen($message) < 20){
	// 	$formok = false;
	// 	$errors[] = "Your message must be greater than 20 characters";
	// }
	
	//send email if all is ok
	if($formok){
		$headers = "From: turnos@javierfurman.com" . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		$emailbody = "<p>Recibiste una nueva solicitud de turno.</p>
					  <p><strong>Nombre: </strong> {$name} </p>
					  <p><strong>Email: </strong> {$email} </p>
					  <p><strong>Teléfono: </strong> {$phone} </p>
					  <p><strong>Consulta: </strong> {$enquiry} </p>
					  <p>Este mensaje fue enviado desde: {$ipaddress} el {$date} a las {$time}</p>";
		
		mail("turnos@javierfurman.com","New Enquiry",$emailbody,$headers);
		
	}
	
	//what we need to return back to our form
	$returndata = array(
		'posted_form_data' => array(
			'name' => $name,
			'email' => $email,
			'phone' => $phone,
			'enquiry' => $enquiry,
			// 'message' => $message
		),
		'form_ok' => $formok,
		'errors' => $errors
	);
		
	
	//if this is not an ajax request
	if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'){
		//set session variables
		session_start();
		$_SESSION['cf_returndata'] = $returndata;
		
		//redirect back to form
		header('location: ' . $_SERVER['HTTP_REFERER']);
	}
}
