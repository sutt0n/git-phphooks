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
				
				echo $line . "\n";
			
			}
			
			die();
			
			exit( $exitCode );
		
		}
		
		protected function hasDebugCode( $filename ) {
		
			// Make sure we're not scanning ourselves
			if( $filename == end(explode("/", $_SERVER['SCRIPT_FILENAME'])) ) {
				return false;
			}
		
			// Read only
			$fp = fopen( $filename, "r" );
			
			$search = [
				"console.log",
				"print_r",
				"var_dump"
			];
			
			$return = false;
			$lineNum = 1;

			while( !feof( $fp ) && ( $line = fgets( $fp ) ) !== false ) {			
				foreach( $search as $pattern ) {
					
					if( strpos( $line, $pattern ) !== false ) {
						$return = true;
						break;
					}
					
				}
				
				// We want to stop scanning if we've got our result
				if( $return ) {
					break;
				}
				
				$lineNum++;
				
			}
			
			// Close the file
			fclose( $fp );
			
			// Set the last line.
			$this->lastLine = $lineNum;
			
			return $return;
			
		
		}
		
	}
	
?>