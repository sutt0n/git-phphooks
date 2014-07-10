<?php

	$precommit = new precommit();

	////////

	class hook {
	
		public function __construct() {
			$this->execute();
		}
		
		/**
		 * To be overridden
		 */
		protected function execute() {
		}
	
		protected function debug( $input, $return = false ) {
		
			$dtNow = new \DateTime("NOW", new \DateTimeZone("America/Chicago"));
			
			$debug = "[DEBUG] [";
			$debug.= $dtNow->format("Y-m-d H:i:s") . "] :: ";
			$debug.= $input;
			$debug.= "\n";
			
			if( $return ) {
				return $debug;
			} else {
				echo $debug;
			}
		
		}
		
	};

	class precommit extends hook {
	
		protected $lines = [];
	
		public function __construct() {
			parent::__construct();
		}
		
		protected function execute() {
			
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
			
			exec('git diff-index --cached --name-status '. $check, $lines);
			
			print_r($lines);
			
			foreach( $lines as $line ) {
			
				$line = trim($line);
				$line = preg_replace("(A|M|\\t)", "", $line);
				
				$this->debug( "File: " . $line );
			
			}
		
		}
		
	}
	
?>