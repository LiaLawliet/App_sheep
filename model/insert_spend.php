<?php 
function insert_spend(array $data){

    try{

        $pdo = get_pdo();
        $pdo->beginTransaction(); // ce met en mode transactionnel
    // les requêtes si une requête échoue => elle lance une exception 

        $pdo->commit();  // les exécutés
        
        return true;



    }catch(Exception $e){

        var_dump($e->getMessage());

        $pdo->rollback(); // si une exception a été retourner par PDO ou PHP on retourne dans l'état initial 
        
        
        return false;

    }
