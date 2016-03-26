<?php

class optionalIf extends Rule {

	private $oAlternative = null;

	public function __construct($sMessage, $oAlternativeField){
		$this->sMessage = $sMessage;
		$this->oAlternative = $oAlternativeField;
	}
	
	public function getValue(){
		return $this->mValue.' alternative field: '.$this->oAlternative;
	}

	private function executeCondition(){
		return !empty($this->mValue);
	}
	
	public function explain(){
		$sExplain = "test is true if \"".$this->mValue."\" is not empty() OR is empty, and \"".$this->oAlternative->getValue()."\" is not empty.";
		$sExplain .= "result: ".var_dump($this->executeCondition(), true);
		return $sExplain;
	}

	
}

?>