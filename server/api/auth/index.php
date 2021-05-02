<?php
	
	//DB Schema
	
	/*rows:

	id                    => int 20 | Auto Increment
	username              => varchar 255
	email                 => varchar 255
	password              => text
	last-ip               => varchar 45
	mode                  => varchar 15
	minecraft_skin_url    => varchar 255
	minecraft_token       => varchar 500
	is_banned_launcher    => varchar 5
	

	*/



	// CONFIG

	//database config
	$host = "127.0.0.1";
    $dbname = "";
    $db_username = "";
    $db_password = "";


    $table = "users";  // Table of your users 


    $mode_email = "email"; // Row of email (no hash)
    $mode_username = "name"; // Row of username (no hash)

    //Connection
    $dsn = "mysql:host=$host;dbname=$dbname";
    try{
        $pdo = new PDO($dsn, $db_username, $db_password);
        // echo "Conection Ok, La base de donnée " . $dbname . " est en ligne avec l'utilisateur " . $db_username . " sur le serveur: " . $host . " !";
    }catch (PDOException $e){
        echo $e->getMessage();
    }


    //code

	header("Content-Type: application/json");

	$password = $_GET['password']; 

	if (isset($_GET['email'])) {
		$user = $_GET['email'];
		$mode = $mode_email;
	} else {
		$user = $_GET['username'];
		$mode = $mode_username;
	}

	if (empty($_GET['password']))
	{
	    http_response_code(400);
	    echo json_encode([
	        'status' => 'error',
	        'error' => 'Password is needed',
	    ]);
	    exit;
	}

	if (empty($user))
	{
	    http_response_code(400);
	    echo json_encode([
	        'status' => 'error',
	        'error' => 'Username/Email is needed',
	    ]);
	    exit;
	}


	$api = $pdo->prepare("SELECT * FROM `$table` WHERE " . "$mode" . " = '$user'");
	$api->execute();

	if (false === $api)
	{
	    http_response_code(500);
	    echo json_encode([
	        'status' => 'error',
	        'error' => 'Bzzzzzzzt',
	    ]);
	    exit;
	}

	$api_use = $api->fetch();

	if (false === $api_use || password_verify($_GET['password'],  $api_use['password']) === false)
	{
	    http_response_code(401);
	    echo json_encode([
	        'status' => 'error',
	        'error' => 'Authentification failed',
	    ]);
	    exit;
	}


	if ($api_use['is_banned_launcher'] !== "0")
	{
	    http_response_code(401);
	    echo json_encode([
	        'status' => 'error',
	        'error' => 'Account is not validate',
	    ]);
	    exit;
	}


	if (strpos($api_use['mode'], "mojang" ) !== false) {
		echo json_encode([
			'id' => $api_use['id'],
		    'username' => $api_use['minecraft_username'],
		    'password' => $api_use['password'],
		    'check' => $api_use['is_banned_launcher'],
		    'mode' => $api_use['mode'],
		    'token' => $api_use['minecraft_token'],
		    'status' => 'ok',
		]);
	} 
	else {
		echo json_encode([
			'id' => $api_use['id'],
		    'username' => $api_use['minecraft_username'],
		    'password' => $api_use['password'],
		    'check' => $api_use['is_banned_launcher'],
		    'mode' => $api_use['mode'],
		    'lien' => $api_use['minecraft_skin_url'],
		    'status' => 'ok',
		]);
	}


?>
