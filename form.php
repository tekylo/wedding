<?php
// Configure your number Prefix and Recipient here
$subject = '[Contact via website]';			// email subject
$emailTo = 'your-email@gmail.com';			// change to your email
$errors = array();							// array to hold validation errors
$data = array();							// array to pass back data
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = stripslashes(trim($_POST['name']));
	$friendName = stripslashes(trim($_POST['friendName']));
	$number = stripslashes(trim($_POST['number']));

	if (empty($name)) {
		$errors['name'] = 'Name is required.';
	}
	if (empty($friendName)) {
		$errors['friendName'] = 'Friend Name is required.';
	}
	// if there are any errors in our errors array, return a success boolean or false
	if (!empty($errors)) {
		$data['success'] = false;
		$data['errors']  = $errors;
	} else {
		$body = '
			<strong>Name: </strong>'.$name.'<br />
			<strong>Friends Name: </strong>'.$friendName.'<br />
			<strong>Contact info: </strong>'.$number.'<br />
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
		$data['message'] = 'Thank you, ' . $name . '! Waiting you on our wedding!';
	}
	// return all our data to an AJAX call
	echo json_encode($data);
}