<?php
namespace wcf\system\template\plugin;
use wcf\system\template\TemplateEngine;
use wcf\system\template\TemplatePluginModifier;
use wcf\util\DateUtil;

/**
 * The 'date' modifier formats a unix timestamp.
 * Default date format contains year, month and day.
 * 
 * Usage:
 * {$timestamp|date}
 * {"132845333"|date:"Y-m-d"}
 *
 * @author 	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	system.template.plugin
 * @category 	Community Framework
 */
class TemplatePluginModifierDate implements TemplatePluginModifier {
	/**
	 * @see TemplatePluginModifier::execute()
	 */
	public function execute($tagArgs, TemplateEngine $tplObj) {
		return DateUtil::format(DateUtil::getDateTimeByTimestamp($tagArgs[0]), (!empty($tagArgs[2]) ? $tagArgs[2] : DateUtil::DATE_FORMAT));
	}
}
