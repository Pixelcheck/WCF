<?php
namespace wcf\system\option\group;
use wcf\system\option\OptionType;

/**
 * Any group permission type should implement this interface.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	system.option.group
 * @category 	Community Framework
 */
interface GroupOptionType extends OptionType {
	/**
	 * Merges the different values of an option to a single value.
	 * 
	 * @param	array		$values
	 * @return	mixed		$value
	 */
	public function merge(array $values);
}
