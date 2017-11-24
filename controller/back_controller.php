<?php

function dashboard(){

	$pdo = get_pdo();
    
    $sql = "
    	SELECT us.user_id, GROUP_CONCAT(u.name) as names, s.price, s.pay_date, us.price as part 
        FROM users as u
        JOIN user_spend as us
        ON us.user_id = u.id
        JOIN spends as s 
        ON s.id = us.spend_id
        GROUP BY s.pay_date
        ORDER BY s.pay_date
        LIMIT 3;
    ";
    
    $res = $pdo->query($sql); // renvoie un objet PDOStmt 

    $totalSpends = getTotalSpend();
    $depenses = getSpendbyUser() ;

    include __DIR__ . '/../views/back/dashboard.php';
    
}

function history(){

	$pdo = get_pdo();

	if (isset($_GET['page'])) {
		$page = ($_GET['page'] - 1) * 5;
	} else {
		$page = 0;
	}
	
	$depenses = getSpendbyUser2($page, PAGINATE);

    include __DIR__ . '/../views/back/history.php';
}

function logout(){
	$_SESSION = array();

	if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"]
    											, $params["domain"]
    											, $params["secure"]
    											, $params["httponly"]);
	}

	session_destroy();

	header('Location: /');
	exit;
}

function add_spend(){
	
	$datas = getSpendbyUser();

	$pdo = get_pdo();

	if( !empty($_POST) ) {

		$title = htmlspecialchars($_POST['title']);
		$description = htmlspecialchars($_POST['description']);
		$price = htmlspecialchars($_POST['price']);
		$date = $_POST['date'];
		$nbspender = count($_POST['name']);
		$priceUser = $price/$nbspender;
		$names = $_POST['name'];

		$prepare = $pdo->prepare("INSERT INTO `spends` (`title`, `description`, `price`,`pay_date`, `status`) VALUES (?,?,?,?,?) ;");
    
        $prepare->bindValue(1, $title);
        $prepare->bindValue(2, $description);
        $prepare->bindValue(3, $price);
        $prepare->bindValue(4, date('Y-m-d'));
        $prepare->bindValue(5, 'paid');

        $prepare->execute();

        $lastID = $pdo->lastInsertID();

        $prepare = null;

		foreach ($_POST['name'] as $name) {

	        $prepare = $pdo->prepare("INSERT INTO `user_spend` (`user_id`, `spend_id`, `price`) VALUES (?,?,?) ;");

            $prepare->bindValue(1, $name);
            $prepare->bindValue(2, $lastID);
            $prepare->bindValue(3, $priceUser);
                
            $prepare->execute(); // pour insérer effectivement  
        }
	}		
	include __DIR__ . '/../views/back/add_spend.php';
}


