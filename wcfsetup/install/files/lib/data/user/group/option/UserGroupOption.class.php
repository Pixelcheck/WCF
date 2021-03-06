<?php
namespace wcf\data\user\group\option;
use wcf\data\option\Option;

/**
 * Represents a usergroup option.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2010 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	data.user.group.option
 * @category 	Community Framework
 */
class UserGroupOption extends Option {
	/**
	 * @see	DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'user_group_option';
	
	/**
	 * @see	DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'optionID';
}
