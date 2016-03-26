<?php

class requiredIfAll extends Rule {
	
	private $aAlernatives = [];

	public function __construct($sMessage, $aAlternativeFields){
		$this->sMessage = $sMessage;
		$this->aAlernatives = $aAlternativeFields;
	}
	
	public function getValue(){
		$aValues = [];
		foreach($this->aAlernatives as $oField){
			$aValues[] = $oField->getValue();
		}
		$aValues[] = $this->mValue;

		return join(" \n ", $aValues);
	}

	private function executeCondition(){
		return !empty($this->mValue);
	}
	
	public function explain(){
		return "test is true if \"".$this->mValue."\" is not empty() IF all of alternatives are filled. result: ".var_dump($this->executeCondition(), true);
	}

}

?>