<?php

/*
 * @author loup
*/

require_once ('Constructor.php');

class LogunForm extends LogunConstructor{
    
    const TYPE_STANDARD = 1;
    const TYPE_AJAX = 2;
    const TYPE_STATIC = 3;
    
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const CALLER_NAME_LENGTH = 4;

    const FORM_SECURITY_FIELD_NAME = 'securityField';
    
    private $aFields = null;
	private $aSetupFields = [];
	private $aParseTools = [];
    private $aRules = null;
    
    private $iType = 0;
    private $sAction = '';
    private $aAttributes = [];
    private $aConfig = [];
    private $aRenderPresets = [];
    private $sMethod = '';
    private $sId = '';
    private $sName = '';
    private $aCallers = [];
	private $aValues = [];
	private $aParseOnSubmit = [];
    private $bSubmitVerified = null;

    private $bValid = true;
    private $sFormSecurityCheckValue = "";
    private $bAlwaysValid = false;

    /**
     *
     * @param string $sName name of form. If there is no id attribute, id is equal to name
     * @param string $sAction [optional]  address of submit action. If null, current url is applied. If type of form is ajax, ajax support is set to default, and action is null, form sends request to constructed url address.
     * @param string $sType [optional] - standard: standard html form <br /> - ajax: results sent over ajax request <br /> - static: submit is disabled
     * @param array $aAttributes    [optional]  <br />-   method: default is POST<br />-   class: class or classes (separated by commas) describing this form<br />-   input_defaults: array of default assigned to all children inputs (additionaly to attributes set in optional attributes of input). eg. [class=>'input'] means that all of inputs will have assigned the input class to their defined classes<br />-   id: if id is not to be the same as form name<br />-   attr:  an array of array pairs - attribute name => attribute value<br />-   js: an array of array pairs - event name => js function
     * @param array $aConfig    [optional]  <br />
     -   ajax_support
     <br />*   default (===null):    logun calls, via default js function, supplied with serialized form, to page URL/FORM_NAME to verify form via ajax<br />*   name of js function: logun calls js function, supplying it with serialized form<br />-   ajax_url_template: sprintf-ready string containing prepared address for ajax validation, overriding default url. Only parameter for replacement if formName (eg. ajax/%s.formcheck.ajax is parsed to ajax/FORM_NAME.formcheck.ajax)<br />-   ajax_js_caller: name of js function to be used instead of default login function. Overwrites only when ajax_support is set to default<br />-   render_type<br />*   default (===null): logun  prepares input fields and forms as constructed html<br />*   template_manager: login uses supplied template manager to construct form<br />-   render_lib = is render_type is set to template_manager, reference to object responsible for constructing templates<br />-   template_dir = if render_type is set to template_manager, this variable sets path under witch logun is supposed to find templates for inputs and forms, to later use in template rendering ()<br />-   render_fetch<br />-   render_assign<br />-   js_lib<br />-   js_support<br />-   i18n_type<br />-   i18n_lib<br />-   captcha_type<br />-   captcha_lib<br />-   upload_lib
     * @return object LogunForm instance
     */
    public function __construct($sName, $sAction = null, $sType = null, $aAttributes = [], $aConfig = []) {
        parent::__construct();
        
        $this->setFormType((empty($sType) ? self::TYPE_STANDARD : $sType));
        $this->setFormAction((empty($sAction) ? LOGUN_CURRENT_URL : $sAction));
        $this->setFormAttributes($aAttributes);
        $this->setFormConfig($aConfig);
        
        $this->aFields = array();
        $this->aRules = array();
        
        $this->aCallers = $this->getLogunCallers();

        $this->sFormSecurityCheckValue = $this->generateSecurityValue();

    }

    private function generateSecurityValue(){
        return md5($this->getFormName() . time() . LOGUN_SECURITY_SALT);
    }
    
    public function setAlwaysValidOverride(){
        $this->bAlwaysValid = true;
    }

    public function setFormType($iType) {
        $this->iType = $iType;
    }
    
    public function setFormAction($sAction) {
        $this->sAction = $sAction;
    }
    
    public function setFormMethod($sMethod) {
        $this->sMethod = $sMethod;
    }
    
    public function setFormId($sId) {
        $this->sId = $sId;
    }
    
    public function setFormName($sName) {
        $this->sName = $sName;
    }
    
    public function setValues($aValues){
      $this->aValues = $aValues;
    }

    public function getFormType() {
        return $this->iType;
    }
    
    public function getFormAction() {
        return $this->sAction;
    }
    
    public function getFormMethod() {
        return $this->sMethod;
    }
    
    public function getFormId() {
        return $this->sId;
    }
    
    public function getFormName() {
        return $this->sName;
    }
    
    public function getFormAttributes() {
        return $this->aAttributes;
    }
    
    public function getRendererDefaults() {
        return $this->aRenderPresets;
    }

	public function addParseTool($sFieldType, $cParseTool){
		//if a parse tool has been set previosly (ie. in form call), do not override it (ie. by input properties)
		if(empty($this->aParseTools[$sFieldType]) 
				&& is_callable($cParseTool)){
			$this->aParseTools[$sFieldType] = $cParseTool;
		}
	}

    private function hasParseTool($sFieldType){
        return !empty($this->aParseTools[$sFieldType]);
    }

    private function getParseTool($sFieldType){
        return $this->aParseTools[$sFieldType];
    }

		private function callParseTool(Input $oField){
        return call_user_func_array($this->getParseTool($oField->getType()), [$oField->getValue()]);
    }

    private function getRequestField($sFieldName){

        switch($this->getFormMethod()){
            case self::METHOD_GET:
                return @$_GET[$sFieldName];    
            break;

            case self::METHOD_POST:
                return @$_POST[$sFieldName];    
            break;
        }
    }

    /**
    * add required fields to the form
    */

    private function setupFormFields(){
        $oFormSecurityField = $this->addSetupField('hidden', self::FORM_SECURITY_FIELD_NAME);
        $oFormSecurityField->setValue($this->sFormSecurityCheckValue);
    }

    private function getInputValue($sInputName){
			if(!empty($this->aValues[$sInputName])){
        return $this->aValues[$sInputName];
      }
      else{
				if(!empty($this->getRequestField($sInputName))){
            return $this->getRequestField($sInputName);
        }
			}

			return null;
    }

    /*attributes [optional]
          -   method: default is POST
          -   class: class or classes (separated by commas) describing this form
          -   input_defaults: array of default assigned to all children inputs (additionaly to attributes set in optional attributes of input). eg. [class=>'input'] means that all of inputs will have assigned the input class to their defined classes
          -   id: if id is not to be the same as form name
          -   attr:  an array of array pairs - attribute name => attribute value
          -   js: an array of array pairs - event name => js function
    */
    
    private function setFormAttributes($aAttributes) {
        if (!empty($aAttributes)) {
            
            if (!empty($aAttributes['method'])) {
                $this->setFormMethod(strtoupper($aAttributes['method']));
            }
            
            if (!empty($aAttributes['id'])) {
                $this->setFormId($aAttributes['id']);
            }
            
            if (!empty($aAttributes['attr'])) {
                $this->aAttributes = $aAttributes['attr'];
            }
            
            if (!empty($aAttributes['class'])) {
                $this->setRendererDefaults('class', $aAttributes['class']);
            }
            
            if (!empty($aAttributes['input_defaults'])) {
                $this->setRendererDefaults('input_defaults', $aAttributes['input_defaults']);
            }
            
            if (!empty($aAttributes['js'])) {
                $this->setRendererDefaults('form_js', $aAttributes['js']);
						}

						if (!empty($aAttributes['parse']) 
								&& is_array($aAttributes['parse'])) {
								foreach($aAttributes['parse'] as $sType	=>	$cTool){
	                $this->addParseTool($sType, $cTool);
								}
            }

        }
    }
    
    private function setRendererDefaults($sKey, $mValue) {
        $this->aRenderPresets[$sKey] = $mValue;
    }
    
    private function setFormConfig() {
    }
    
    /*
     * @param string $aArray
    */
    public function constructFromArray() {
    }
    
    public function constructFromTemplate() {
    }
    
    private function construct() {
    }
    
    /*
     *
     *
    */
    
		private function setInput($sName){
        if (file_exists(LOGUN_PATH_INPUTS . $sName . LOGUN_INPUT_EXTENSION)) {
            require_once (LOGUN_PATH_INPUTS . $sName . LOGUN_INPUT_EXTENSION);
            
            if (class_exists($sName)) {
                return true;
            }
        }
        
        die('Input type "' . $sName . '" is not supported');
    }

    private function setRule($sName){
        if (file_exists(LOGUN_PATH_VALIDATORS . $sName . LOGUN_VALIDATOR_EXTENSION)) {
            require_once (LOGUN_PATH_VALIDATORS . $sName . LOGUN_VALIDATOR_EXTENSION);
            
            if (class_exists($sName)) {
                return true;
                
            }
        }
        
        die('Validator type "' . $sName . '" is not supported');
    }

    private function input($name, $arguments) {

				$sName = strtolower($name);

				if(!empty($this->getField($sName))){
					die('field named "'.$sName.'" already exist! Remember - field names are CASE INSENSITIVE');
					return false;
				}

        $arguments[0] = empty($arguments[0]) ? '' : $arguments[0];
        $arguments[1] = empty($arguments[1]) ? '' : $arguments[1];
        $arguments[2] = empty($arguments[2]) ? [] : $arguments[2];
        
        if($this->setInput($name)){
            list($sName, $sLabel, $aArguments) = $arguments;
                
            return $this->addField(new $name($sName, $sLabel, $aArguments));    
        }
    }
    
    /**
     *add validation rules to form
     *constructor:
     * error message / if i18n is set, message identifier
     * argument
     * array of other arguments
     */
    
    private function rule($sRuleName, $arguments) {
        $arguments[0] = empty($arguments[0]) ? '' : $arguments[0];
        $arguments[1] = empty($arguments[1]) ? '' : $arguments[1];
        $arguments[2] = empty($arguments[2]) ? [] : $arguments[2];
        
        if ($this->setRule($sRuleName)) {
            list($sMessage, $basicQuantifier, $aArguments) = $arguments;
        
            return $this->addRule(new $sRuleName($sMessage, $basicQuantifier, $aArguments));
        }
        
        die('Validation rule "' . $sRuleName . '" is not supported');
    }

		/*
		 *@param Input $oInput form field 
		 *@param Array $aInputSetup array
		 *@return void
		 *
		 *@description check if input field $oInput has specyfic prerequisites for rendering form
		 */

		private function setupInput(Input $oInput, $aInputSetup){
        if(!empty($aInputSetup)){

						if(!empty($aInputSetup['rules'])){
	            foreach($aInputSetup['rules'] as $sRuleName => $aRuleParams){
                $oInput->addValidator($this->rule($sRuleName, $aRuleParams));
							}
						}

						if(!empty($aInputSetup['form'])){
						 	if(!empty($aInputSetup['form']['attributes'])){
								$this->setFormAttributes(['attr'	=>	$aInputSetup['form']['attributes']]);
							}
						 	if(!empty($aInputSetup['form']['parse'])){
								$this->setFormAttributes(['parse'	=>	$aInputSetup['form']['parse']]);
							}
						}
        }
    }

    private function addField(Input $oInput) {
        $sInputName = $oInput->getName();

        if(empty($oInput->getValue()) || $this->verifySubmit()){
            $oInput->setValue($this->getInputValue($sInputName));
        }
        
        $this->aFields[$sInputName] = $oInput; 
        $this->setupInput($oInput, $oInput->getInputSetup());

        return $oInput;
    }

		private function getField($sFieldName){
			if(in_array($sFieldName, array_keys($this->aFields))){
				return $this->aFields[$sFieldName];
			}
			return null;
		}

    private function addSetupField($sType, $sName, $aAttributes = []) {
        if($this->setInput($sType)){
            $oInput = new $sType($sName);
            $this->aSetupFields[$oInput->getName()] = $oInput;
            return $oInput;
        }
    }

    private function addRule(Rule $oRule) {
        if($oRule->getRuleType() == 'form'){
          $this->aRules[] = $oRule;
        } 

        return $oRule;
    }
    
    public function getHtmlFormHeader($aAdditionalParams = []) {
        $sFormBase = '<form';
        
        /*switch($this->getFormType()){
        case self::TYPE_AJAX:
        
        break;
        case self::TYPE_STANDARD:
        
        break;
        case self::TYPE_STATIC:
        
        break;
        }*/
        
        $aParams = $this->getFormAttributes() + $aAdditionalParams;
        
        $aParams['method'] = $this->getFormMethod();
        $aParams['action'] = $this->getFormAction();
        $aParams['name'] = $this->getFormName();
        $aParams['id'] = $this->getFormId();
        
        foreach ($aParams as $sAttrName => $sAttrValue) {
            if (!empty($sAttrValue)) {
                $sFormBase.= ' ' . $sAttrName . '="' . $sAttrValue . '"';
            }
        }
        
        $sFormBase.= ' />';
        
        return $sFormBase;
    }
    
    public function getHtmlFormFooter() {
        $this->setupFormFields();

        $sFormClose = '';

        foreach($this->aSetupFields as $oField){
            $sFormClose .= $oField->getHtml() . "\n";
        }

        $sFormClose .= '</form>';

        return $sFormClose;
    }
    
    public function getFields() {
        return $this->aFields;
    }
    
    public function verifySubmit(){
        if($this->bAlwaysValid){
            print 'Always Valid Override Is On'."\n";
            return true;
        }

        if($this->bSubmitVerified !== null ){
            return $this->bSubmitVerified;
        }

        if(empty($_SESSION[self::FORM_SECURITY_FIELD_NAME])){
            $_SESSION[self::FORM_SECURITY_FIELD_NAME] = $this->sFormSecurityCheckValue;
        }

        if($this->getRequestField(self::FORM_SECURITY_FIELD_NAME) == $_SESSION[self::FORM_SECURITY_FIELD_NAME]){
            $this->bSubmitVerified = true;
        }
        else{
            $this->bSubmitVerified = false;
        } 

        $_SESSION[self::FORM_SECURITY_FIELD_NAME] = $this->sFormSecurityCheckValue;


        return $this->bSubmitVerified;
    }

		private function parseFormValues(){
			
		}

    private function renderActions(){
        if($this->verifySubmit()){
					$this->validate();

					if($this->isValid()){
						$this->parseFormValues();
					}
        }
    }

		private function get($sFieldName){
			$sFieldName = strtolower($sFieldName);

			$oRequestedField = $this->getField($sFieldName);
			if(!empty($oRequestedField)){
				return $oRequestedField->getValue();
			}

			return null;
		}

    public function getArray() {
        $this->renderActions();        
        return (new LogunRenderer($this))->getOutput(LogunRenderer::LOGUN_RENDER_ARRAY);
    }
    
    public function getHtmlArray() {
        $this->renderActions();
        return (new LogunRenderer($this))->getOutput(LogunRenderer::LOGUN_RENDER_ARRAY_HTML);
    }

    public function validate(){
      foreach($this->aFields as $oField){
        if(!$oField->validate()){
            $this->bValid = false;
            print $oField->getName() . ' is not valid' . "<br />\n";
        }
        else{
            print $oField->getName() . ' is valid' . "<br />\n";
            if($this->hasParseTool($oField->getType())){
                $this->callParseTool($oField);    
            }
        }
      }
    }

    public function isValid(){
        return $this->bValid;
    }

    public function __toString() {
        $this->renderActions();
        return (new LogunRenderer($this))->getOutput();
    }

    public function __call($name, $arguments) {
        
        //caller name if first 4 chars of function. All callers are available in private 'callers' array. If caller name is not in supported
        //callers array, assume that functino is a input call
				$sCallerName = substr($name, 0, strcspn($name, 'ABCDEFGHJIJKLMNOPQRSTUVWXYZ'));
				
				if (in_array($sCallerName, $this->aCallers)) {
            $sCalleeNameParam = lcfirst(substr($name, strcspn($name, 'ABCDEFGHJIJKLMNOPQRSTUVWXYZ')));
            return $this->$sCallerName($sCalleeNameParam, $arguments);
        }
        
        //see if there is an input file with supplied name
        return $this->input($name, $arguments);
    }
}
