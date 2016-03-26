<?php

class uploadSuccess extends Rule {
	
	public function __construct($sMessage){
		$this->sMessage = $sMessage;
	}

	private function executeCondition(){
			$mFileStatus = $this->getValue()['error'];
			
			if(is_array($mFileStatus)){
				foreach($mFileStatus as $iFileIndex	=>	$iFileStatus){
					if($iFileStatus != 0){
						return false;
					}
				}
			}
			else{
				if($iFileStatus != 0){
					return false;
				}			
			}
	}
	
	public function explain(){
		return "test is true if all files are of uploaded with error code 0. result: ".var_dump($this->executeCondition(), true);
	}

}

?>
