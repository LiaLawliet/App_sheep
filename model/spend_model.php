<?php

function spend_model($args = '*', $where = null, $limit = 10 ){

	$pdo = get_pdo();
	$prepare = $pdo->prepare("SELECT '.$args.' FROM spends WHERE 1=1 LIMIT ?"); // ...
	$prepare->bindValue(1, $args, PDO::PARAM_INT );
	$prepare->bindValue(2, $limit, PDO::PARAM_INT );
    $prepare->execute();

    return $prepare->fetchAll();
}

function getTotalSpend(){

	$pdo = get_pdo();

	$prepare = $pdo->prepare("SELECT SUM(price) AS total from spends;");

	$prepare->execute();

	if ( $data = $prepare->fetch() ) {
		return $data['total'];
	}

	return 0;
}

function getSpendbyUser(){
	$pdo = get_pdo();

	$prepare = $pdo->prepare("
		SELECT name, user_id, SUM(price) as price
		FROM user_spend AS us 
		INNER JOIN users AS u 
		ON u.id = us.user_id 
		GROUP BY user_id;
	");


	$prepare->execute();

	return $prepare->fetchAll();
}

function getSpendByUserPart($limit, $offset = 0){

    $pdo = get_pdo();
    
    if ($offset == 0) {
        $sql = "
        SELECT us.user_id, GROUP_CONCAT(u.name) as names, s.price, s.pay_date, us.price as part 
        FROM users as u
        JOIN user_spend as us
        ON us.user_id = u.id
        JOIN spends as s 
        ON s.id = us.spend_id
        GROUP BY s.pay_date
        ORDER BY s.pay_date
        LIMIT $limit;
    ";
    } else {
        $sql = "
        SELECT us.user_id, GROUP_CONCAT(u.name) as name, s.price, s.pay_date, us.price as part 
        FROM users as u
        JOIN user_spend as us
        ON us.user_id = u.id
        JOIN spends as s 
        ON s.id = us.spend_id
        GROUP BY s.pay_date
        ORDER BY s.pay_date
        LIMIT $limit, $offset;
    ";
    } 
    $prepare = $pdo->prepare($sql);
    $prepare->execute();
    return $prepare->fetchAll();
}

function getSpendbyUser2($page, $nb_element){
	$pdo = get_pdo();
    
    $sql = "
    	SELECT u.name as name, us.price as price, s.pay_date as pay_date
    	FROM users as u 
    	JOIN user_spend as us 
    	ON us.user_id = u.id 
    	JOIN spends as s 
    	ON s.id = us.spend_id
    	ORDER BY s.pay_date desc 
    	LIMIT ?, ?;
    ";

    $prepare = $pdo->prepare($sql); // renvoie un objet PDOStmt 

    $prepare->bindValue(1, $page, PDO::PARAM_INT);
    $prepare->bindValue(2, $nb_element, PDO::PARAM_INT);

    $prepare->execute();

    return $prepare->fetchAll();
}

function countspend(){
	$pdo = get_pdo();

	$sql = "
    	SELECT COUNT(id) as nb_total
    	FROM spends;
    ";

    $prepare = $pdo->prepare($sql); // renvoie un objet PDOStmt 

    $prepare->execute();

    $datata = $prepare->fetch();

    return $datata;

}

function getUsers(){
	$pdo = get_pdo();

	$prepare = $pdo->prepare("
		SELECT name
		FROM users;
	");


	$prepare->execute();

	return $prepare->fetchAll();
}