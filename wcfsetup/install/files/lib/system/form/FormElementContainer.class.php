<?php
namespace wcf\system\form;
use wcf\system\form\FormElement;

/**
 * Interface for form element containers.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	system.form
 * @category 	Community Framework
 */
interface FormElementContainer {
	/**
	 * Returns help message.
	 *
	 * @return	string
	 */
	public function getDescription();
	
	/**
	 * Sets help message.
	 *
	 * @param	string		$description
	 */
	public function setDescription($description);
	
	/**
	 * Returns label.
	 *
	 * @return	string
	 */
	public function getLabel();
	
	/**
	 * Sets label.
	 *
	 * @param	string		$label
	 */
	public function setLabel($label);
	
	/**
	 * Returns the value of child element with given name.
	 *
	 * @param	string		$key
	 * @return	mixed
	 */
	public function getValue($key);
	
	/**
	 * Returns a list of child elements.
	 *
	 * @return	array<FormElement>
	 */
	public function getChildren();
	
	/**
	 * Appends a new child to stack.
	 *
	 * @param	FormElement		$element
	 */
	public function appendChild(FormElement $element);
	
	/**
	 * Preprens a new child to stack.
	 *
	 * @param	FormElement		$element
	 */
	public function prependChild(FormElement $element);
	
	/**
	 * Handles a POST or GET request.
	 *
	 * @param	array		$variables
	 */
	public function handleRequest(array $variables);
	
	/**
	 * Returns HTML-representation of current form element container.
	 *
	 * @param	string		$formName
	 * @return	string
	 */
	public function getHTML($formName);
}
