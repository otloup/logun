<?php

class maxLength extends Rule {

	private $iMaxLength = 9999;
	private $sName = "maxLength";

	public function __construct($sMessage, $iLength){
		$this->sMessage = $sMessage;
		$this->iMaxLength = $iLength;
	}

	private function executeCondition(){
		return !empty($this->mValue);
	}
	
	public function explain(){
		return "test is true if \"".$this->mValue."\" has length smaller or equal to ".$this->iMaxLength." characters . result: ".var_dump($this->executeCondition(), true);
	}
}

?>
