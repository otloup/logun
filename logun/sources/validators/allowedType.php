<?php

class allowedType extends Rule {
	
	public function __construct($sMessage){
		$this->sMessage = $sMessage;
	}

	private function executeCondition(){
		$aFiletypes = explode(',', $this->getBasicAttribute());

		if(empty($aFiletypes)){
			return true;
		}
		else{

			$allowedType = function($sType) use($aFiletypes){
				return in_array($sType, $aFiletypes);
			};

			$mFiletype = $this->getValue()['type'];
			if(is_array($mFiletype)){
				foreach($mFiletype as $iFileIndex	=>	$sFileType){
					if(!$allowedType($sFileType)){
						return false;
					}
				}
			}
			else{
				if(!$allowedType($mFiletype)){
					return false;
				}			
			}
		}
	}
	
	public function explain(){
		return "test is true if all files are of types \"".$this->getBasicAttribute()."\" result: ".var_dump($this->executeCondition(), true);
	}

}

?>
