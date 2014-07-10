<?php

	$lines = [];
	$status = 0;
	$check = "";
	$rc = 0;
	
	$debug = [
		"console.log",
		"print_r",
		"var_dump"
	];
	
	exec('git rev-parse --verify HEAD 2> /dev/null', $lines, $rc);
	
	if( $rc ) {
		$check = "4b825dc642cb6eb9a060e54bf8d69288fbee4904";
	} else {
		$check = "HEAD";
	}
	
	exec("git diff-index --cached --name-status $check | egrep '^(A|M)' | awk '{print $2;}'", $lines);
	
	print_r($lines);
	
	foreach( $lines as $line ) {
	
		echo "line: ". $line;
	
	}
	
?>