<?php
namespace wcf\acp\page;
use wcf\system\menu\acp\ACPMenu;
use wcf\page\AbstractPage;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\cache\source\MemcacheAdapter;
use wcf\system\cache\CacheHandler;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\WCF;
use wcf\util\FileUtil;

/**
 * Shows a list of all cache resources.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	acp.page
 * @category 	Community Framework
 */
class CacheListPage extends AbstractPage {
	// system
	public $templateName = 'cacheList';
	public $neededPermissions = array('admin.system.canViewLog');
	public $cleared = 0;
	
	/**
	 * contains a list of cache resources
	 *
	 * @var	array
	 */
	public $caches = array();
	
	/**
	 * contains general cache information
	 *
	 * @var array
	 */
	public $cacheData = array();
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['cleared'])) $this->cleared = intval($_REQUEST['cleared']);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// init cache data
		$this->cacheData = array(
			'source' => get_class(CacheHandler::getInstance()->getCacheSource()),
			'version' => '',
			'size' => 0,
			'files' => 0
		);
		
		// filesystem cache
		if ($this->cacheData['source'] == 'wcf\system\cache\source\DiskCacheSource') {
			// set version
			$this->cacheData['version'] = WCF_VERSION;
			
			$conditions = new PreparedStatementConditionBuilder();
			$conditions->add("packageID IN (?)", array(PackageDependencyHandler::getDependencies()));
			$conditions->add("standalone = ?", array(1));
			
			// get package dirs
			$sql = "SELECT	packageDir
				FROM	wcf".WCF_N."_package
				".$conditions;
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditions->getParameters());
			while ($row = $statement->fetchArray()) {
				$packageDir = FileUtil::getRealPath(WCF_DIR.$row['packageDir']);
				$cacheDir = $packageDir.'cache';
				if (file_exists($cacheDir)) {
					$this->caches[$cacheDir] = array();

					// get files in cache directory
					$files = glob($cacheDir.'/*.php');
					// get additional file information
					if (is_array($files)) {
						foreach ($files as $file) {
							$filesize = filesize($file);
							$this->caches[$cacheDir][] = array(
								'filename' => basename($file),
								'filesize' => $filesize,
								'mtime' => filemtime($file),
								'perm' => substr(sprintf('%o', fileperms($file)), -3),
								'writable' => is_writable($file)
							);
							
							$this->cacheData['files']++;
							$this->cacheData['size'] += $filesize;
						}
					}
				}
			}
		}
		// memcache
		else if ($this->cacheData['source'] == 'wcf\system\cache\source\MemcacheCacheSource') {
			// get version
			$this->cacheData['version'] = MemcacheAdapter::getInstance()->getMemcache()->getVersion();
			
			// get stats
			$stats = MemcacheAdapter::getInstance()->getMemcache()->getStats();
			$this->cacheData['files'] = $stats['curr_items'];
			$this->cacheData['size'] = $stats['bytes'];
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'caches' => $this->caches,
			'cacheData' => $this->cacheData,
			'cleared' => $this->cleared
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		ACPMenu::getInstance()->setActiveMenuItem('wcf.acp.menu.link.log.cache');
		
		parent::show();
	}
}
