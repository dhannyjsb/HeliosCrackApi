<?php
	
	//DB Schema
	
	/*rows:

	id              => int 20 | Auto Increment
	username        => varchar 255
	email           => varchar 255
	password        => text
	check           => int 10
	last-ip         => varchar 45
	user-mode       => varchar 15
	uuid            => varchar 200
	link            => varchar 200


	*/



	// CONFIG

	//database config
	$host = '127.0.0.1';
    $dbname = '';
    $table = '';
    $username = 'root';
    $password = '';

    $mode_email = "email"; // Row of email (no hash)
    $mode_username = "username"; // Row of username (no hash)
    $table_last_ip = "last-ip"

    //Connection
    $dsn = "mysql:host=$host;dbname=$dbname";
    try{
        $pdo = new PDO($dsn, $username, $password);
        // echo "Conection Ok, La base de donnée " . $dbname . " est en ligne avec l'utilisateur " . $username . " sur le serveur: " . $host . " !";
    }catch (PDOException $e){
        echo $e->getMessage();
    }


    //code

	header("Content-Type: application/json");

	if (!isset($_GET['username']) || empty($_GET['username']))
	{
	    http_response_code(400);
	    echo json_encode([
	        'status' => 'error',
	        'error' => 'username is needed',
	    ]);
	    exit;
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

	if (isset($_GET['email'])) {
		$user = $_GET['email'];
		$mode = $mode_email;
	} else {
		$user = $_GET['username'];
		$mode = $mode_username;
	}
	
	$password = $_GET['password']; 


	$api = $pdo->prepare("SELECT * FROM `$table` WHERE '$mode' = '$user'");
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

	if (false === $api_use || $password != $api_use['password'])
	{
	    http_response_code(401);
	    echo json_encode([
	        'status' => 'error',
	        'error' => 'Authentification failed',
	    ]);
	    exit;
	}


	if ("1" != $api_use['check'])
	{
	    http_response_code(401);
	    echo json_encode([
	        'status' => 'error',
	        'error' => 'Account is not validate',
	    ]);
	    exit;
	}
	$stmt = $pdo->prepare("UPDATE `$table` SET `$table_last_ip` = ? WHERE '$mode' = '$user'");
	$stmt->execute([$_SERVER['REMOTE_ADDR']]);


	if (strpos($api_use['mode'], "uuid" ) !== false) {
		echo json_encode([
		    'username' => $api_use['username'],
		    'password' => $api_use['password'],
		    'check' => $api_use['check'],
		    'mode' => $api_use['mode'],
		    'uuid' => $api_use['uuid'],
		    'status' => 'ok',
		]);
	} 
	else {
		echo json_encode([
		    'username' => $api_use['username'],
		    'password' => $api_use['password'],
		    'check' => $api_use['check'],
		    'mode' => $api_use['mode'],
		    'lien' => $api_use['lien'],
		    'status' => 'ok',
		]);
	}

?>
