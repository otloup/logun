<?php

class maxFilesize extends Rule {
	
	public function __construct($sMessage){
		$this->sMessage = $sMessage;
	}

	private function executeCondition(){
		$mFilesize= $this->getValue()['size'];

		if(empty($mFilesize)){
			return false;
		}

		$iAllowedSize = $this->getBasicQuantifier();

		$validSize = function($iFilesize) use($iAllowedSize){
			return ($iFilesize >= $iAllowedSize);
		};

		if(is_array($mFilesize)){
			foreach($mFilesize as $iFileIndex => $iFileSize){
				if(!$validSize($iFileSize)){
					return false;
				}
			}
		}
		else{
			if(!$validSize($iFileSize)){
				return false;
			}		
		}
	}
	
	public function explain(){
		return "test is true if file size of all of files is not greater than \"".$this->getBasicQuantofier()."\". result: ".var_dump($this->executeCondition(), true);
	}

}

?>
