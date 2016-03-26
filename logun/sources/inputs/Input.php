<?php

	class Input implements InputInterface {
		private $aRules = array();
		private $sName = '';
		private $sLabel = '';
		private $aAttributes = array();
		private $mValue = null;
		private $sType = '';
		private $bValid = true;
		private $aFailedRules = [];
		private $aPassedRules = [];

		public function __construct($sName, $sLabel = null, $aAttributes = null){
			$this->setName($sName);
			if(!empty($sLabel)){
				$this->setLabel($sLabel);
			}

			if(!empty($aAttributes)){
				$this->setAttributes($aAttributes);
			}
		}

		public function setName($sName){
			$this->sName = $sName;
		}
		
		public function setLabel($sLabel){
			$this->sLabel = $sLabel;
		}
		
		public function setAttributes($aAttributes, $sType = 'input'){
			if(!empty($aAttributes)){
				foreach($aAttributes as $sName	=>	$sValue){
					$this->aAttributes[$sType][$sName] = $sValue;
				}
			}
		}

		public function getName(){
			return $this->sName;
		}
		
		public function getValue(){
			return $this->mValue;
		}
		
		public function getLabel(){
			return $this->sLabel;
		}

		public function getLabelHtml(){
			if(empty($this->sLabel)){
				return '';
			}

			$sName = $this->getName();
			$sLabel = $this->getLabel();
			$aAttributes = $this->getAttributes('label');

			$sHtml = '<label for="'.$sName.'"';

			if(!empty($aAttributes)){
				foreach($aAttributes as $sAttributeName => $mAttributeValue){
					$sHtml .= ' ' . $sAttributeName . '="' . $mAttributeValue . '"';
				}
			}


			$sHtml .= '>';
			$sHtml .= $sLabel;
			$sHtml .= '</label>';

			return $sHtml;
		}

		public function getAttributes($sType = null){
			if(empty($this->aAttributes)){
				return null;
			}

			return empty($sType) ? $this->aAttributes : (empty($this->aAttributes[$sType]) ? null : $this->aAttributes[$sType]);
		}

		public function getType(){
			return $this->sType;
		}

		public function getValidity(){
			return $this->bValid;
		}

		public function getFailedRules(){
			return $this->aFailedRules;
		}

		public function getPassedRules(){
			return $this->aPassedRules;
		}

		public function getAssignedRules(){
			return $this->aRules;
		}

		public function getAssignedRuleTypes(){
			return array_keys($this->getAssignedRules());
		}

		public function getErrorMessages(){
			$sMessages = '';

			if(!empty($this->aFailedRules)){
				foreach($this->aFailedRules as $oRule){
					$sMessages .= $oRule->getMessage() . "<br />\n";
				}
			}

			return $sMessages;
		}

		public function getErrorMessage(){
			if(!empty($this->aFailedRules)){
				return array_values($this->aFailedRules)[0]->getMessage();
			}
		}

		public function hasRule($sRuleName){
			return in_array($sRuleName, array_keys($this->aRules));
		}

		public function prepHtml(){
			$sName = $this->getName();
			$aAttributes = $this->getAttributes('input');
			$mValue = $this->getValue();
			$sType = $this->getType();
			$sAttributes = '';

			if(!empty($aAttributes)){
				foreach($aAttributes as $sAttributeName => $mAttributeValue){
					$sAttributes .= ' '.$sAttributeName.'="'.$mAttributeValue.'"';
				}
			}

			return [
				'name'	=>	$sName
				,'id'	=>	$sName
				,'value'	=>	$mValue
				,'type'	=> $sType
				,'attributes'	=> $sAttributes
			];
		}

		public function getHtml(){
			$aParams = $this->prepHtml();

			return '<input type="'.$aParams['type'].'" name="'.$aParams['name'].'" id="'.$aParams['id'].'" value="'.$aParams['value'].'" '.$aParams['attributes'].' />';
		}
		
		public function addValidator(Rule $rValidator){
			$this->aRules[$rValidator->getName()] = $rValidator;
		}

		public function addValidators($aValidators){
			foreach($aValidators as $rValidator){
				if($rValidator instanceof Rule){
					$this->addValidator($rValidator);
				}
			}
		}
		
		public function setValue($mValue){
			$this->mValue = $mValue;
		}
		
		public function printInput(){
			echo $this->getHtml();
		}

		public function validate(){
			foreach($this->aRules as $oRule){
				if(!$oRule->validate($this->getValue())){
					$this->bValid = false;
					$this->aFailedRules[$oRule->getName()] = $oRule;
				}
				else{
					$this->aPassedRules[$oRule->getName()] = $oRule;
				}
			}

			return $this->getValidity();
		}

		public function getInputSetup(){}
	}

?>
