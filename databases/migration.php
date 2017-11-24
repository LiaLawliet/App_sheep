<?php

require_once __DIR__ . '/../vendor/fzaninotto/faker/src/autoload.php';
$faker = Faker\Factory::create();

define('DB_SEED', true);
define('NUMBER_USER', 5);

 $defaults = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
// trois arguments pour se connecter à la base données le premier c'est la chaîne de connexion, le deuxième c'est le user et le dernier pass
$pdo = new PDO('mysql:host=localhost;dbname=db_sheep', 'root', '', $defaults);
print_r($pdo);

$users = "
	CREATE TABLE `users` (
		`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(100) NOT NULL,
        `password` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `avatar`VARCHAR(100) NULL DEFAULT NULL,
         UNIQUE KEY `users_email_unique` (`email`),
        PRIMARY KEY (`id`)
	)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$parts = "
	CREATE TABLE `parts`(
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` INT UNSIGNED NULL DEFAULT NULL,
        `day` SMALLINT NOT NULL,
        `started` DATETIME NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        CONSTRAINT `parts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    );
";

$spends = "
    CREATE TABLE `spends`(
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(100) NOT NULL,
        `description` VARCHAR(100) NOT NULL,
        `price` DECIMAL(7,2) NOT NULL,
        `pay_date` DATETIME NULL DEFAULT NULL,
        `status` ENUM('in progress', 'canceled', 'paid') NOT NULL DEFAULT 'in progress',
        PRIMARY KEY (`id`)
    );
";

$balances = "
    CREATE TABLE `balances`(
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` INT UNSIGNED NULL DEFAULT NULL,
        `pricePart` DECIMAL(7,2) NOT NULL,
        `priceStay` DECIMAL(7,2) NOT NULL,
        `priceDebit` DECIMAL(7,2) NOT NULL,
        `priceCredit` DECIMAL(7,2) NOT NULL,
        PRIMARY KEY (`id`),
        CONSTRAINT `balances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    );
";

$user_spend = "
    CREATE TABLE `user_spend`(
        `user_id` INT UNSIGNED NULL DEFAULT NULL,
        `spend_id` INT UNSIGNED NULL DEFAULT NULL,
        `price` DECIMAL(7,2) NOT NULL,
        CONSTRAINT `user_spend_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        CONSTRAINT `user_spend_spend_id_foreign` FOREIGN KEY (`spend_id`) REFERENCES `spends` (`id`) ON DELETE CASCADE
    );
";

$pdo->exec("DROP TABLE IF EXISTS user_spend");
$pdo->exec("DROP TABLE IF EXISTS parts");
$pdo->exec("DROP TABLE IF EXISTS balances");
$pdo->exec("DROP TABLE IF EXISTS users");
$pdo->exec("DROP TABLE IF EXISTS spends");

$pdo->exec($users) ;
$pdo->exec($spends) ;
$pdo->exec($balances) ;
$pdo->exec($parts) ;
$pdo->exec($user_spend) ;

if (DB_SEED == true){

    /*$pdo->query(sprintf(
        "INSERT INTO `users` (`name`, `email`, `password`) VALUES ('%s','%s','%s') ;", 
        $faker->name,
        $faker->unique()->email,
        'admin'
        )
    ) ;*/

    //requête préparée => PDO compile cette partie de la requête
    $prepare = $pdo->prepare("INSERT INTO `users` (`name`, `email`, `password`) VALUES (?,?,?) ;");

    for($i=0;$i<5;$i++){
    
        $prepare->bindValue(1, $faker->name);
        $prepare->bindValue(2, $faker->unique()->email);
        $prepare->bindValue(3, 'admin');

        $prepare->execute(); //insérer les données dans la table users
    
    }

    $prepare = null; // écrase la précédente requête préparée

    //requête préparée => PDO compile cette partie de la requête
    $prepare = $pdo->prepare("INSERT INTO `spends` (`title`, `description`, `price`,`pay_date`, `status`) VALUES (?,?,?,?,?) ;");

    for($i=0;$i<30;$i++){
    
        $prepare->bindValue(1, $faker->word);
        $prepare->bindValue(2, $faker->text);
        $prepare->bindValue(3, $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 5000));
        $t = 60*24*3600;
        $d = rand(0,$t);
        
        $prepare->bindValue(4, date('Y-m-d h:i:s', time() - $d ));
        $prepare->bindValue(5, $faker->randomElement(['paid','in progress','canceled']));

        $prepare->execute();
    
    }

    $queryDepend = $pdo->query('SELECT id, price FROM spends');
    
    $depends = $queryDepend->fetchAll(); // récupère toutes les lignes de la table spends, sous forme d'un tableau de tableau
    

    $prepare = null; // écrase la précédente requête préparée

    function aleaUserIds($nbIds, $maxUser)
{

    $ids = [];
    
    
    while(count($ids) < $nbIds){
        
        $choiceId = rand(1, $maxUser);
        
        while(in_array($choiceId, $ids) == true ) $choiceId = rand(1, $maxUser);
            
        $ids[] =  $choiceId;
        
    }
    
    return $ids;

}


    $prepareUser_spend = $pdo->prepare('INSERT INTO `user_spend` (`user_id`, `spend_id`, `price`) VALUES (?, ?, ?) '); 
     
     // récupérer toutes les dépendances 
     $queryDepend = $pdo->query('SELECT id, price FROM spends');

     $depends = $queryDepend->fetchAll(); // tableau de tableau associatif
     
     $queryCountUser = $pdo->query('SELECT COUNT(id) as total FROM users');
     
     $totalUser = $queryCountUser->fetch()['total']; // renvoie avec PDO une ligne 
     
     
     foreach($depends as $depend){
     
        if($depend['price'] > 1000){
            
            $nbUser = rand(2, NUMBER_USER);
            $priceUser = round($depend['price'] / $nbUser,2);
            
            $ids = aleaUserIds($nbUser, NUMBER_USER); // fonction permettant de récupérer les ids aléatoirement
            
            for($i = 0; $i < $nbUser; $i++)
            {
            
                $prepareUser_spend->bindValue(1,$ids[$i]);
                $prepareUser_spend->bindValue(2,$depend['id']);
                $prepareUser_spend->bindValue(3, $priceUser);
                
                $prepareUser_spend->execute(); // pour insérer effectivement
            
            }
        
        }else{
        
            $prepareUser_spend->bindValue(1, rand(1, NUMBER_USER));
            $prepareUser_spend->bindValue(2,$depend['id']);
            $prepareUser_spend->bindValue(3, $depend['price']);

            $prepareUser_spend->execute(); // pour insérer effectivement
            
        
        }
     
     }
     
     $prepareUser_spend = null;
     $queryDepend = null;
     
}
