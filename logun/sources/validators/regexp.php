<?php

class regexp extends Rule {

	public function __construct($sMessage){
		$this->sMessage = $sMessage;
	}

	private function executeCondition(){
		return !empty($this->mValue);
	}
	
	public function explain(){
		return "test is true if \"".$this->mValue."\" has satisfied regexp condition: \"...\". result: ".var_dump($this->executeCondition(), true);
	}
}

?>