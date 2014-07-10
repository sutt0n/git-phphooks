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
	
		protected function debug( $input, $color = "blue" ) {
		
			$colorCode = "";
			
			switch( $color ) {
				default:
				case "blue":
					$colorCode = "[34m";
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

			`echo -e {$debug}`;
		
		}
		
	};

	class precommit extends hook {
	
		protected $lines = null;		
		protected $lastLine = null;
	
		public function __construct() {
			parent::__construct();
		}
		
		protected function execute() {
			
			$files = [];
			$status = 0;
			$check = "";
			$rc = 0;
			
			exec('git rev-parse --verify HEAD 2> /dev/null', $output, $rc);
			
			if( $rc ) {
				$check = "4b825dc642cb6eb9a060e54bf8d69288fbee4904";
			} else {
				$check = "HEAD";
			}
			
			exec('git diff-index --cached --name-status '. $check, $files);
			
			$exitCode = 0;
			
			foreach( $files as $file ) {
			
				$file = trim($file);
				$file = preg_replace("((A|M)\s)", "", $file);
				
				$this->debug("Scanning file " . $file . " for debug code.");
				
				if( $this->hasDebugCode( $file ) ) {
					$this->debug("Debug code found on line " . $this->lastLine . " in " . $file .".", "red");
					$exitCode = 1;
				}
			
			}
			
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