<?php
// Configure your number Prefix and Recipient here
$subject = 'RSVP';			// email subject
$emailTo = 'samarybaltasar@gmail.com';			// change to your email
$errors = array();							// array to hold validation errors
$data = array();							// array to pass back data
$day = array();
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = stripslashes(trim($_POST['name']));
	$friendName = stripslashes(trim($_POST['friendName']));
	$number = stripslashes(trim($_POST['number']));
	foreach (stripslashes(trim($_POST['day'])) as $item) {
        $day[] = $item;
    }
    $food   = stripslashes(trim($_POST['food']));

    if (empty($food)) {
        $food = 'Ninguna';
    }
    $day = $_POST['day'];

	if (empty($name)) {
		$errors['name'] = 'Nombre es requerido.';
	}

    if (empty($number)) {
        $errors['number'] = 'Teléfono o correo es requerido.';
    }

    if (empty($day[0]) && empty($day[1])) {
        $errors['day'] = 'Día es requerido.';
    }
	// if there are any errors in our errors array, return a success boolean or false
	if (!empty($errors)) {
		$data['success'] = false;
		$data['errors']  = $errors;
	} else {
		$body = '
			<strong>Nombre: </strong>'.$name.'<br />
			<strong>Acompañante: </strong>'.$friendName.'<br />
			<strong>Días: </strong>'.$day[0].' '.$day[1].'<br />
			<strong>Alergia: </strong>'.$food.'<br />
			<strong>Información de Contacto: </strong>'.$number.'<br />
		';
		$headers  = "MIME-Version: 1.1" . PHP_EOL;
		$headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
		$headers .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
		$headers .= "Date: " . date('r', $_SERVER['REQUEST_TIME']) . PHP_EOL;
		$headers .= "Message-ID: <" . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>' . PHP_EOL;
		$headers .= "From: " . "=?UTF-8?B?".base64_encode($name)."?=" . PHP_EOL;
		$headers .= "Return-Path: $emailTo" . PHP_EOL;
		$headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;
		$headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'] . PHP_EOL;
		mail($emailTo, "=?utf-8?B?" . base64_encode($subject) . "?=", $body, $headers);
		$data['success'] = true;
		// Change the Success message here
		$data['message'] = 'Gracias, ' . $name . ' ¡Te esperamos en nuestra boda!';
	}
	// return all our data to an AJAX call
	echo json_encode($data);
}
