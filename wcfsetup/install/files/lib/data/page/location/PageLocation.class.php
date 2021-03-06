<?php
namespace wcf\data\page\location;
use wcf\data\DatabaseObject;

/**
 * Represents a page location.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	data.page.location
 * @category 	Community Framework
 */
class PageLocation extends DatabaseObject {
	/**
	 * @see	DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'page_location';
	
	/**
	 * @see	DatabaseObject::$databaseIndexName
	 */
	protected static $databaseIndexName = 'locationID';
}
