<?php

	$precommit = new precommit();

	class hook {
	
		public function __construct() {
			$this->execute();
		}
		
		/**
		 * To be overridden
		 */
		protected function execute() {
		}
	
		protected function debug( $input, $color = "blue" ) {
	
			// *Note: Colors don't work atm... I'll figure it out later.
		
			$colorCode = "";
			
			switch( $color ) {
				default:
				case "blue":
					$colorCode = "[31m";
					break;
					
				case "red":
					$colorCode = "[31m";
					break;
			}
		
			$dtNow = new \DateTime("NOW", new \DateTimeZone("America/Chicago"));
			
			$debug = "\e" . $colorCode;
			$debug.= "[DEBUG] ";
			$debug.= $dtNow->format("Y-m-d H:i:s") . " :: "; 
			$debug.= $input;
			$debug.= "\e[0m";
			$debug.= "\n";

			echo $debug;
		
		}
		
	};

	class precommit extends hook {
	
		protected $lines = null;		
		protected $lastLine = null;
	
		public function __construct() {
			parent::__construct();
		}
		
		protected function execute() {
			
			$lines = null;
			
			exec('git diff HEAD | cat', $lines);
			
			// existing files changed:	pre-commit.php | 22 ++++++----------------\
			// new files: 				New Text Document.txt | 0
			// deleted files:			New Text Document.txt | 0		
			
			foreach( $lines as $line ) {
			
				$line = trim( $line );
				
				// Only looking at additions
				if( @$line[0] != "+" ) {
					continue;
				}
				
				foreach( $search as $pattern ) {
					
				}
			
			}
			
			die();
			
			exit( $exitCode );
		
		}
		
	}
	
?>