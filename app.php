<?php  

session_start();

# constantes de l'application
define('SALT', 'SALT123');
define('TOKEN_TIME', 5); // 5 minutes
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DBNAME', 'db_sheep');
define('PAGINATE', 5);

# autoloader 
require __DIR__.'/library/helpers.php';
require __DIR__.'/model/spend_model.php';
require __DIR__.'/controller/front_controller.php';
require __DIR__ . '/controller/back_controller.php';

// sécurise les chaînes de caractères qui passe dans l'url 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$method = $_SERVER['REQUEST_METHOD'];

