<?php

class nonNumeric extends Rule {

	private $sName = "nonNumeric";

	public function __construct($sMessage){
		$this->sMessage = $sMessage;
	}

	private function executeCondition(){
		return !empty($this->mValue);
	}
	
	public function explain(){
		return "test is true if \"".$this->mValue."\" has no numerical value. result: ".var_dump($this->executeCondition(), true);
	}
}

?>
