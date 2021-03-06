<?php
namespace wcf\acp\form;
use wcf\acp\option\Options;
use wcf\data\option\OptionAction;
use wcf\system\exception\UserInputException;
use wcf\system\WCFACP;
use wcf\util\XML;

/**
 * Shows the option import form.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	acp.form
 * @category 	Community Framework
 */
class OptionImportForm extends ACPForm {
	/**
	 * @see AbstractPage::$templateName
	 */
	public $templateName = 'optionImport';
	
	/**
	 * @see ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.option.importAndExport';
	
	/**
	 * @see AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.system.canEditOption');
	
	/**
	 * upload file data
	 * @var array
	 */
	public $optionImport = null;
	
	/**
	 * list of options
	 * @var array
	 */
	public $options = array();
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_FILES['optionImport'])) $this->optionImport = $_FILES['optionImport'];
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		// upload
		if ($this->optionImport && $this->optionImport['error'] != 4) {
			if ($this->optionImport['error'] != 0) {
				throw new UserInputException('optionImport', 'uploadFailed');
			}
			
			try {
				$xml = new XML($this->optionImport['tmp_name']);
				$optionsXML = $xml->getElementTree('options');
				foreach ($optionsXML['children'] as $option) {
					$name = $value = '';
					foreach ($option['children'] as $optionData) {
						switch ($optionData['name']) {
							case 'name':
								$name = $optionData['cdata'];
								break;
							case 'value':
								$value = $optionData['cdata'];
								break;
						}
					}
					
					if (!empty($name)) {
						$this->options[$name] = $value;
					}
				}
			}
			catch (SystemException $e) {
				throw new UserInputException('optionImport', 'importFailed');
			}
		}
		else {
			throw new UserInputException('optionImport');
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save
		$optionAction = new OptionAction(array(), 'import', array('data' => $this->options));
		$optionAction->executeAction();
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// check master password
		WCFACP::checkMasterPassword();
		
		parent::show();
	}
}
