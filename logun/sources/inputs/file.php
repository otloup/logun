<?php

class file extends Input {
	private $sType = 'file';

	public function getType(){
		return $this->sType;
	}

	private function getArrayValue($aFile){
		$aParsed = $aFile;
		$iLength = count($aFile['error']);

		for($i = 0;$i < $iLength; $i++){
			$sFileTargetPath = LOGUN_PATH_TMP.md5_file($aParsed['tmp_name'][$i]).'_'.urlencode($aParsed['name'][$i]);
		
			if(is_readable($sFileTargetPath)){
				$aParsed['path'][$i] = $sFileTargetPath;
				$this->setValue($aParsed);
			}
			else {
				$aParsed['path'][$i] = $aParsed['tmp_name'][$i];
				$this->setValue($aParsed);
			}
		}

		unset($aParsed['tmp_name']);

		return $aParsed;
	}

	private function getSingleValue($aFile){
		$aParsed = $aFile;
		$sFileTargetPath = LOGUN_PATH_TMP.md5_file($aParsed['tmp_name']).'_'.urlencode($aParsed['name']);
		
		if(is_readable($sFileTargetPath)){
			$aParsed['path'] = $sFileTargetPath;
			unset($aParsed['tmp_name']);
			$this->setValue($aParsed);
		}
		else {
			$aParsed['path'] = $aParsed['tmp_name'];
			unset($aParsed['tmp_name']);
			$this->setValue($aParsed);
		}

		return $aParsed;
	}

	public function getValue(){
		$mValue = array();

		if(!empty($_FILES[$this->getName()])){
			$mValue = $_FILES[$this->getName()];
			
			if(!empty($mValue['size'][0])){
				return $this->getArrayValue($mValue);
			}

			return $this->getSingleValue($mValue);

		}
	}

	public function parse($mValue){
		$copy = function($sFilePath, $sFileName){
			$sPath = LOGUN_PATH_TMP.md5_file($sFilePath).'_'.urlencode($sFileName);
			move_uploaded_file($sFilePath, $sPath);

			if(is_readable($sPath)){
				return $sPath;
			}
			else{
				die($sPath." is not readable");
			}

			return false;
		};

		$mReturn = $mValue;

		if(!empty($mValue)){
			if(is_writeable(LOGUN_PATH_TMP)){
				if(is_array($mValue['path'])){
					foreach($mValue['path'] as $iFileIndex => $sFileTmpPath){
						$mReturn['path'][$iFileIndex] = $copy($sFileTmpPath, $mValue['name'][$iFileIndex]);
					}
				}
				else{
					$mReturn['path'] = $copy($mValue['path'], $mValue['name']);				
				}
			}
			else{
				die(LOGUN_PATH_TMP." is not writeable");
			}
		}

		return $mReturn;
	}

	public function prepHtml(){
		$aParams = parent::prepHtml();
		$aParams['value'] = '';

		if(!empty($this->getAttributes('input')['multiple'])){
			$aParams['name'] = $this->getName().'[]';
		}

		return $aParams;
	}

	public function getInputSetup(){
		return [
				'form'	=>	[
					'attributes'	=>	[
						'enctype'	=>	"multipart/form-data"
					]
					,'parse'	=>	[
						'file'	=>	[$this, 'parse']
					]
				]
				,'rules'	=> [
					'uploadSuccess'	=>	['file upload has encountered a problem']
					,'maxFilesize'	=>	['file exceeses maximum allowed size', LOGUN_MAX_FILESIZE]
					,'allowedType'	=>	['file is not of allowed filetype ('.LOGUN_ALLOWED_FILETYPES.')', LOGUN_ALLOWED_FILETYPES]
				]
			];
	}

}

?>
