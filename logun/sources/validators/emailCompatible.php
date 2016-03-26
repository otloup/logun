<?php

class emailCompatible extends Rule {

	private function executeCondition(){
		return false;//!empty($this->mValue);
	}
	
	public function explain(){
		return "test is true if \"".$this->mValue."\" has format of an email address. result: ".var_dump($this->executeCondition(), true);
	}
}

?>
