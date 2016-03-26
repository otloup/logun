<?php

	class Rule implements RuleInterface {

		private $sMessage = "";
		private $mValue = null;
		private $sType = "input";
		private $sName = "plainRule";
		private $mBasicQuantifier = null;
		private $aArguments = [];

		public function __construct($sMessage, $mBasicQuantifier = null, $aArguments = []){
			$this->sMessage = $sMessage;
			$this->mBasicQuantifier = $mBasicQuantifier;
			$this->aArguments = $aArguments;
		}

		protected function getBasicQuantifier(){
			return $this->mBasicQuantifier;
		}

		protected function getArguments(){
			return $this->aArguments;
		}

		public function getName(){
			return get_class($this);
		}

		public function getMessage(){
			return $this->sMessage;
		}
		
		private function getValue(){
			return $this->mValue;
		}

		public function getRuleType(){
			return $this->sType;
		}

		public function setValue($mValue){
			$this->mValue = $mValue;
		}

		private function executeCondition(){
			return !empty($this->mValue);
		}
		
		public function explain(){
			return "test is true if \"".$this->mValue."\" is not empty(). result: ".var_dump($this->executeCondition(), true);
		}

		public function validate($mValue){
			$this->setValue($mValue);
			return $this->executeCondition();
		}

	}

?>
