<?php

require_once __DIR__.'/../app.php';

if( $uri === '/' ){

	index();

}elseif( $uri === '/add_user' ){

    add_user();

}elseif( $uri === '/auth' && $method == 'POST' ){

    auth();

}elseif( $uri == '/admin' ){

	if( !isset($_SESSION['auth']) ){
		$_SESSION['message'] = "Vous n'avez pas les autorisations pour visiter cette page";
		header('Location: /');
	    exit;
	}

	dashboard();

}elseif($uri === '/history' || $uri === "/history/"){

	if( !isset($_SESSION['auth']) ){
		$_SESSION['message'] = "Vous n'avez pas les autorisations pour visiter cette page";
		header('Location: /');
	    exit;
	}

	history();

}elseif ( $uri === '/add_spend' ) {

	if( !isset($_SESSION['auth']) ){
		$_SESSION['message'] = "Vous n'avez pas l'autorisation";
		header('Location: /');
		exit;
	}

	add_spend();

}elseif ( $uri === '/logout') {

	if( !isset($_SESSION['auth']) ){
		$_SESSION['message'] = "Vous n'avez pas l'autorisation";
		header('Location: /');
		exit;
	}

	logout();

}else {

    header('HTTP/1.1 404 Not Found');
    echo $uri;
    echo '<html><body><h1>Page Not Found</h1></body></html>';

}
    
    

