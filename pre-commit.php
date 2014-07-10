<?php

	$lines = [];
	$status = 0;
	
	$debug = [
		"console.log",
		"print_r",
		"var_dump"
	];
	
	exec('git diff --name-only', $lines);
	
	//exec('git rev-parse --verify HEAD 2> /dev/null', $output);
	
	print_r($lines);
	
	foreach( $lines as $line ) {
	
		echo "line: ". $line;
	
	}
	
?>