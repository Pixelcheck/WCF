<?php
namespace wcf\system\application;
use wcf\system\cache\CacheHandler;
use wcf\system\WCF;

/**
 * Default implementation for all applications for the community framework.
 * 
 * @author 	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	system
 * @category 	Community Framework
 */
abstract class AbstractApplication implements Application {
	/**
	 * @see	Application::__callStatic()
	 */
	public static function __callStatic($method, array $arguments) {
		return call_user_func_array(array('wcf\system\WCF', $method), $arguments);
	}
}
