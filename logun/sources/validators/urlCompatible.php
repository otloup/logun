<?php

class urlCompatible extends Rule {

	public function __construct($sMessage){
		$this->sMessage = $sMessage;
	}

	private function executeCondition(){
		return !empty($this->mValue);
	}
	
	public function explain(){
		return "test is true if \"".$this->mValue."\" has format of a URL address. result: ".var_dump($this->executeCondition(), true);
	}

	public function getInputSetup(){
		return [
			'rules'	=>	[
				'urlCompatible'	=>	[]
			]
		];
	}
}

?>