<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class select extends Input {
	private $sType = 'select';
	private $aData = array();
	private $oParent = null;

	public function __construct($sName, $sLabel, $aData, $oParent = null, $aAttributes = []){
		parent::__construct($sName, $sLabel, $aAttributes);

		if(is_a($oParent, get_class($this)) 
			&& (
				!$oParent->hasParent()
				|| !$oParent->isParent($this)
			)
			&& $oParent != $this
		){
			$this->setParent($oParent);
		}

		$this->setData($aData);
	}

	public function setParent(select $oParent){
		$this->oParent = $oParent;
	}

	public function getParent(){
		return $this->oParent;
	}

	public function isParent(select $oParent){
		$_oParent = $this->getParent();

		if(!empty($_oParent)){
			if($_oParent->isParent($oParent)){
				return true;
			}
		}

		return $_oParent == $oParent;
	}

	public function hasParent(){
		return !($this->getParent() == null);
	}

	public function getType(){
		return $this->sType;
	}

	public function getData(){
		return $this->aData;
	}

	public function setData($aData){
		$this->aData = $aData;
	}

	public function setValue($mValue){
		if(is_array($mValue)){
			$this->setData($mValue);
		}
	}

	private function setupOptions(){
		$aData = $this->getData();
		$aAttributes = $this->getAttributes('option');
		$sAttributesHtml = '';
		$bHasParentData = false;

		if(!empty($aAttributes)){
			foreach($aAttributes as $sAttributeName => $mAttributeValue){
				$sAttributesHtml .= ' '.$sAttributeName.'="'.$mAttributeValue.'"';
			}
		}

		$optionsHtml = function($_aData) use ($sAttributesHtml){
			$sParsedOptions = '';

			foreach($_aData as $_key => $_val){
				$sParsedOptions .= '<option '.$sAttributesHtml.' value="'.$_key.'" >'.$_val.'</option>'."\n";
			}

			return $sParsedOptions;
		};
		
		if($this->hasParent()){
			$aParentData = array_keys($this->getParent()->getData());
			$bHasParentData = true;
		}
		
		$sOptions = '';

		if(!empty($aData)){
			foreach($aData as $sOptionKey => $mOptionValue){
				if(is_array($mOptionValue)){
					//if has parent data, check if key is in parents keys
					if($bHasParentData){
						//if has parent data, proceed as usual
						$sOptions .= $optionsHtml($mOptionValue);
					}
					else{
						$sOptions .= '<optgroup label="'.$sOptionKey.'">'."\n\t".$optionsHtml($mOptionValue)."\n".'</optgroup>';
					}
				}
				else{
					$sOptions .= $optionsHtml([$sOptionKey => $mOptionValue]);
				}
			}
		}

		return $sOptions;
	}

	public function getHtml(){
		$sName = $this->getName();
		$aAttributes = $this->getAttributes('input');

		$sHtml = '<select name="'.$sName.'" id="'.$sName;

		if(!empty($aAttributes)){
			foreach($aAttributes as $sAttributeName => $mAttributeValue){
				$sHtml .= ' '.$sAttributeName.'="'.$mAttributeValue.'"';
			}
		}

		$sHtml .= '>';

		$sHtml .= $this->setupOptions();

		$sHtml .= ' </select>';

		return $sHtml;
	}
}
