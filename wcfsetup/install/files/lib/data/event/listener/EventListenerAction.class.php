<?php
namespace wcf\data\event\listener;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * Executes event listener-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	data.event
 * @category 	Community Framework
 */
class EventListenerAction extends AbstractDatabaseObjectAction {
	/**
	 * @see AbstractDatabaseObjectAction::$className
	 */
	protected $className = 'wcf\data\event\listener\EventListenerEditor';
}
