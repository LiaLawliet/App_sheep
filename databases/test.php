<?php

$userIds = range(1,10);


$ids = [];


$count = 4 ;


while( count($ids) < $count)
{

	$choiceId = rand(1,10);
    
  	while( in_array($choiceId, $ids) == true ) 
    {
    
    	$choiceId = rand(1,10);
    
    }
    
    $ids[] = $choiceId;
    
}


print_r($ids);