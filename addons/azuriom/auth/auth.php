<?php 

	include("MCAuth.class.php");
	$MCAuth = new MCAuth();

	$host = "127.0.0.1";
    $dbname = "web_cms";
    $db_username = "web_cms";
    $db_password = "soleil123";

    $dsn = "mysql:host=$host;dbname=$dbname";
    try{
        $pdo = new PDO($dsn, $db_username, $db_password);
    }catch (PDOException $e){
        echo $e->getMessage();
    }

    $sql_cms_is_mojang = "SELECT * FROM users WHERE id = '$user->id'";
    $query_cms_is_mojang = $pdo->query($sql_cms_is_mojang); 
    $info_cms_is_mojang = $query_cms_is_mojang->fetch();

	if (isset($_POST['email_minecraft']) && isset($_POST['password_minecraft'])) {
		$id = $_POST['id'];
		if (strpos($_POST['mode'], "link") !== false) {
			if ($MCAuth->authenticate($_POST['email_minecraft'], $_POST['password_minecraft']) == TRUE) {
				$account_id = $MCAuth->account['id'];
				$account_username =  $MCAuth->account['username'];
				$account_token =  $MCAuth->account['token'];

				$sql = "UPDATE users SET is_mojang=?, mode=?, minecraft_token=?, minecraft_username=?, minecraft_skin_status=? WHERE id=?";
				$pdo->prepare($sql)->execute(['1', 'mojang', $account_token, $account_username, 'Vous utilisez vôtre skin mojang', $id]);	
				echo "Compte lié a " . $account_username;
				header("Refresh:2");
			} else {
				echo $MCAuth->autherr;
			}
		} else {
			try {
				$sql = "UPDATE users SET is_mojang=?, mode=?, minecraft_token=?, minecraft_username=? WHERE id=?";
				$pdo->prepare($sql)->execute(['0', 'skin_url', '000', "unlink", $id]);	
				echo "Voilà, votre compte n'est plus lié a mojang";
				header("Refresh:2");
			} catch (Exception $e) {
				echo $e;
			}
		}
	}

	elseif (isset($_GET['email_minecraft']) && isset($_GET['password_minecraft'])) {
		$id = $_GET['id'];
		if (strpos($_GET['mode'], "link") !== false) {
			if ($MCAuth->authenticate($_GET['email_minecraft'], $_GET['password_minecraft']) == TRUE) {
				$account_id = $MCAuth->account['id'];
				$account_username =  $MCAuth->account['username'];
				$account_token =  $MCAuth->account['token'];

				$sql = "UPDATE users SET is_mojang=?, mode=?, minecraft_token=?, minecraft_username=?, minecraft_skin_status=? WHERE id=?";
				$pdo->prepare($sql)->execute(['1', 'mojang', $account_token, $account_username, 'Vous utilisez vôtre skin mojang', $id]);	
				echo "Compte lié a " . $account_username;
				header("Refresh:2");
			} else {
				echo $MCAuth->autherr;
			}
		} else {
			try {
				$sql = "UPDATE users SET is_mojang=?, mode=?, minecraft_token=?, minecraft_username=? WHERE id=?";
				$pdo->prepare($sql)->execute(['0', 'skin_url', '000', "unlink", $id]);	
				echo "Voilà, votre compte n'est plus lié a mojang";
				header("Refresh:2");
			} catch (Exception $e) {
				echo $e;
			}
		}
	}

	else {
		echo "Error";
	}


