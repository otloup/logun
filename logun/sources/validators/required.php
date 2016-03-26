<?php

class required extends Rule {
	
	public function __construct($sMessage){
		$this->sMessage = $sMessage;
	}

	private function executeCondition(){
		return !empty($this->mValue);
	}
	
	public function explain(){
		return "test is true if \"".$this->mValue."\" is not empty(). result: ".var_dump($this->executeCondition(), true);
	}

}

?>