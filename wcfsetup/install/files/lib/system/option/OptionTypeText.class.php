<?php
namespace wcf\system\option;
use wcf\data\option\Option;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\option\OptionType;
use wcf\system\option\SearchableUserOption;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * OptionTypeText is an implementation of OptionType for 'input type="text"' tags.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	system.option
 * @category 	Community Framework
 */
class OptionTypeText implements OptionType, SearchableUserOption {
	/**
	 * input type
	 * @var string
	 */
	protected $inputType = 'text';
	
	/**
	 * @see OptionType::getFormElement()
	 */
	public function getFormElement(Option $option, $value) {
		WCF::getTPL()->assign(array(
			'option' => $option,
			'inputType' => $this->inputType,
			'value' => $value
		));
		return WCF::getTPL()->fetch('optionTypeText');
	}
	
	/**
	 * @see OptionType::validate()
	 */
	public function validate(Option $option, $newValue) {}
	
	/**
	 * @see OptionType::getData()
	 */
	public function getData(Option $option, $newValue) {
		return $newValue;
	}
	
	/**
	 * @see SearchableUserOption::getSearchFormElement()
	 */
	public function getSearchFormElement(Option $option, $value) {
		return $this->getFormElement($optionData, $value);
	}
	
	/**
	 * @see SearchableUserOption::getCondition()
	 */
	public function getCondition(PreparedStatementConditionBuilder &$conditions, Option $option, $value) {
		$value = StringUtil::trim($value);
		if (empty($value)) return false;
		
		$conditions->add("option_value.userOption".$option->optionID." LIKE ?", array('%'.addcslashes($value, '_%').'%'));
		return true;
	}
}
