<?php

	$output = [];
	$status = 0;
	
	$debug = [
		"console.log",
		"print_r",
		"var_dump"
	];
	
	exec('git diff --name-only', $lines);
	
	foreach( $lines as $line ) {
	
		echo $line;  
	
	}
	
?>