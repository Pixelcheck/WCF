<?php
namespace wcf\data\option;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\ValidateActionException;
use wcf\system\WCF;

/**
 * Executes option-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	data.
 * @category 	Community Framework
 */
class OptionAction extends AbstractDatabaseObjectAction {
	/**
	 * @see AbstractDatabaseObjectAction::$className
	 */
	protected $className = 'wcf\data\option\OptionEditor';
	
	/**
	 * @see	AbstractDatabaseObjectAction::$permissionsCreate
	 */
	protected $permissionsCreate = array('admin.system.canEditOption');
	
	/**
	 * @see	AbstractDatabaseObjectAction::$permissionsDelete
	 */
	protected $permissionsDelete = array('admin.system.canEditOption');
	
	/**
	 * @see	AbstractDatabaseObjectAction::$permissionsUpdate
	 */
	protected $permissionsUpdate = array('admin.system.canEditOption');
	
	/**
	 * Validates permissions and parameters.
	 */
	public function validateImport() {
		parent::validateCreate(); 
	}
	
	/**
	 * Validates permissions and parameters.
	 */
	public function validateUpdateAll() {
		parent::validateCreate(); 
	}
	
	/**
	 * Imports options.
	 */
	public function import() {
		// create data
		call_user_func(array($this->className, 'import'), $this->parameters['data']);
	}
	
	/**
	 * Updates the value of all given options.
	 */
	public function updateAll() {
		// create data
		call_user_func(array($this->className, 'updateAll'), $this->parameters['data']);
	}
}
