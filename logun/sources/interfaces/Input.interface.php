<?php

	interface InputInterface {

		public function getName();
		public function getValue();
		public function getAttributes();
		public function getHtml();
		public function getLabel();
		public function getType();

		public function addValidator(Rule $rValidator);
		public function addValidators($aValidators);
		
		public function setName($sName);
		public function setValue($mValue);
		public function setAttributes($aAttributes);
		public function setLabel($sLabel);
		
		public function printInput();
		public function validate();
		public function getInputSetup();

	}

?>
