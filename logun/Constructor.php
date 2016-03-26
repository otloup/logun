<?php
	
	class LogunConstructor {

		public function __construct(){
			//inject and parse configuration
			require_once('config.php');

			//inject interfaces and parent classes
			require_once(LOGUN_PATH_INTERFACES.'Input'.LOGUN_INTERFACE_EXTENSION);
			require_once(LOGUN_PATH_INTERFACES.'Rule'.LOGUN_INTERFACE_EXTENSION);

			require_once(LOGUN_PATH_INPUTS.'Input'.LOGUN_SOURCE_EXTENSION);
			require_once(LOGUN_PATH_VALIDATORS.'Rule'.LOGUN_SOURCE_EXTENSION);

			require_once(LOGUN_PATH_ABSOLUTE.'LogunRenderer.php');			

			if(empty(session_id())){
				session_start();
			}
		}

		/**
		* method returning all supplied callers for Logun forms
		* intention is to later add more callers via plugins
		*/

		protected function getLogunCallers(){
			return array(
					'rule'
					,'get'
				);
		}

	}

?>
