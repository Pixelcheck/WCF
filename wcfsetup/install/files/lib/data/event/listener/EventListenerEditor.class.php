<?php
namespace wcf\data\event\listener;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit event listener.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	data.event.listener
 * @category 	Community Framework
 */
class EventListenerEditor extends DatabaseObjectEditor {
	/**
	 * @see	DatabaseObjectEditor::$baseClass
	 */
	public static $baseClass = 'wcf\data\event\listener\EventListener';
}
