<?php

class minLength extends Rule {

	private $iMinLength = 9999;
	private $sName = "minLength";

	public function __construct($sMessage, $iLength){
		$this->sMessage = $sMessage;
		$this->iMinLength = $iLength;
	}

	private function executeCondition(){
		return !empty($this->mValue);
	}
	
	public function explain(){
		return "test is true if \"".$this->mValue."\" has length larger or equal to ".$this->iMinLength." characters . result: ".var_dump($this->executeCondition(), true);
	}
}

?>
