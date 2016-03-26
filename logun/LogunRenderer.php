<?php

	class LogunRenderer {

		const LOGUN_RENDER_STRING = 1;
		const LOGUN_RENDER_ARRAY = 2;
		const LOGUN_RENDER_ARRAY_HTML = 3;

		private $sForm = '';
		private $aDefaults = [];
		private $aExtractedDefaults = [];
		private $aFields = [];
		private $sFormHeader = '';
		private $sFormFooter = '';


		public function __construct(LogunForm $oForm){

			$this->aFields = $oForm->getFields();
			$this->aDefaults = $oForm->getRendererDefaults();
			$this->extractInputDefaults();

			$this->sFormHeader = empty($this->aDefaults['class']) ? $oForm->getHtmlFormHeader() : $oForm->getHtmlFormHeader([
					'class'	=>	$this->aDefaults['class']
				]);

			$this->addToOutput($this->sFormHeader);

			foreach($this->aFields as $oField){
				$sInputType = $oField->getType();
				$oField->setAttributes($this->getDefaultAttributes($sInputType, 'label'), 'label');
				$oField->setAttributes($this->getDefaultAttributes($sInputType));
				
				$this->addToOutput($oField->getLabelHtml());
				$this->addToOutput($oField->getHtml());
			}

			$this->sFormFooter = $oForm->getHtmlFormFooter();

			$this->addToOutput($this->sFormFooter);
		}

		private function extractInputDefaults(){
			foreach($this->aDefaults['input_defaults'] as $sAttribute => $aAssignationRules){
				foreach($aAssignationRules as $sAssigneeName => $mAssignatedData){
					$this->aExtractedDefaults[$sAssigneeName][$sAttribute] = $mAssignatedData;
				}
			}
		} 

		private function getDefaultAttributes($sName, $sType = ''){
			$sPrefix = $sType != '' ? $sType.'_' : '';
			$sKey = $sPrefix.$sName;
			$sAllDefaults = $sPrefix.'all';
			$aAllDefaults = [];

			//get general and specyfic values
			if(!empty($this->aExtractedDefaults[$sKey])){
				$aElementDefaults = $this->aExtractedDefaults[$sKey];
			}

			
			if(!empty($this->aExtractedDefaults[$sAllDefaults])){
				$aAllDefaults = $this->aExtractedDefaults[$sAllDefaults];
			}

			//if element is in general attributes, append its value to specyfic attribute 
			if(!empty($aElementDefaults)){
				foreach($aElementDefaults as $sAttribute => $sData){
					if(!empty($aAllDefaults[$sAttribute])
						&& $aAllDefaults[$sAttribute] != $sData){
						$aAllDefaults[$sAttribute] = $sData . ' ' . $aAllDefaults[$sAttribute];
					}
					else{
						$aAllDefaults[$sAttribute] = $sData;
					}
				}
			}

			return $aAllDefaults;
		}

		private function addToOutput($sString){
			$this->sForm .= $sString . LOGUN_RENDER_LINE_END;
		}

		private function createInputBlowoutArray(Input $oInput){
			$aCompiledConstruct = [];
			$aRules = [];

			$aStandardConstruct = [
				'label'	=>	$oInput->getLabelHtml()
				,'html'	=>	$oInput->getHtml()
				,'value'	=>	$oInput->getValue()
				,'name'	=>	$oInput->getName()
				,'label_text'	=>	$oInput->getLabel()
				,'passed_rules'	=>	join(",", array_keys($oInput->getPassedRules()))
				,'failed_rules'	=>	join(",", array_keys($oInput->getFailedRules()))
				,'error_message'	=>	$oInput->getErrorMessage()
				,'error_messages'	=>	$oInput->getErrorMessages()
			];

			foreach($oInput->getAssignedRuleTypes() as $sType){
				$aRules['is'.ucfirst($sType)] = true;
			}

			$aCompiledConstruct = array_merge($aStandardConstruct, $aRules);

			return $aCompiledConstruct;
		}

		private function getFormArray($bRender = false){
			$aOutput = [];

			$aOutput['formHeader'] = $this->sFormHeader;

			foreach($this->aFields as $oField){
				if($bRender){
					$mOutputValue = $this->createInputBlowoutArray($oField);
				}
				else{
					$mOutputValue = $oField;
				}

				$aOutput[$oField->getName()] = $mOutputValue;
			}

			$aOutput['formFooter'] = $this->sFormFooter;

			return $aOutput;
		}

		public function getOutput($iType = self::LOGUN_RENDER_STRING){
			switch($iType){
				case self::LOGUN_RENDER_STRING:
					return $this->sForm;
				
				case self::LOGUN_RENDER_ARRAY:
					return $this->getFormArray();

				case self::LOGUN_RENDER_ARRAY_HTML:
					return $this->getFormArray(true);
			}
		}

	}

?>
