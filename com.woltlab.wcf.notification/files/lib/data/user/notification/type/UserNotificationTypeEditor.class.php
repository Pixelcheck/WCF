<?php
namespace wcf\data\user\notification\type;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit user notification types.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.notification
 * @subpackage	data.user.notification.type
 * @category 	Community Framework
 */
class UserNotificationTypeEditor extends DatabaseObjectEditor {
	/**
	 * @see	DatabaseObjectEditor::$baseClass
	 */
	protected static $baseClass = 'wcf\data\user\notification\type\UserNotificationType';
}
