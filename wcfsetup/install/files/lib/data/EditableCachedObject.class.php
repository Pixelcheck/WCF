<?php
namespace wcf\data;

/**
 * Abstract class for all cached data holder objects.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	data
 * @category 	Community Framework
 */
interface EditableCachedObject extends EditableObject {
	/**
	 * Resets the cache of this object type.
	 */
	public static function resetCache();
}
