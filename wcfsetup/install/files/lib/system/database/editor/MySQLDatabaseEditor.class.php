<?php
namespace wcf\system\database\editor;
use wcf\system\database\Database;

/**
 * This is the database editor implementation for MySQL4.1 or higher.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	system.database.editor
 * @category 	Community Framework
 */
class MySQLDatabaseEditor extends DatabaseEditor {
	/**
	 * @see DatabaseEditor::getTableNames()
	 */
	public function getTableNames() {
		$existingTables = array();
		$sql = "SHOW TABLES FROM `".$this->dbObj->getDatabaseName()."`";
		$statement = $this->dbObj->prepareStatement($sql);
		$statement->execute();
		while ($row = $statement->fetchArray(\PDO::FETCH_NUM)) {
			$existingTables[] = $row[0];
		}
		return $existingTables;
	}
	
	/**
	 * @see DatabaseEditor::getColumns()
	 */
	public function getColumns($tableName) {
		$columns = array();
		$sql = "SHOW COLUMNS FROM ".$tableName;
		$statement = $this->dbObj->prepareStatement($sql);
		$statement->execute();
		while ($row = $statement->fetchArray()) {
      	 		$columns[] = $row['Field'];
   		}
   		return $columns;
	}
	
	/**
	 * @see DatabaseEditor::getIndices()
	 */
	public function getIndices($tableName) {
		$indices = array();
		$sql = "SHOW INDEX FROM ".$tableName;
		$statement = $this->dbObj->prepareStatement($sql);
		$statement->execute();
		while ($row = $statement->fetchArray()) {
      	 		$indices[] = $index['Key_name'];
   		}
   		
   		return $indices;
	}
	
	/**
	 * @see DatabaseEditor::createTable()
	 */
	public function createTable($tableName, $columns, $indices = array()) {
		$columnDefinition = $indexDefinition = '';
		
		// build column definition
		foreach ($columns as $column) {
			if (!empty($columnDefinition)) $columnDefinition .= ',';
			$columnDefinition .= $this->buildColumnDefinition($column['name'], $column['data']);
		}
		
		// build index definition
		$hasFulltextIndex = false;
		foreach ($indices as $index) {
			if (!empty($indexDefinition)) $indexDefinition .= ',';
			$indexDefinition .= $this->buildIndexDefinition($index['name'], $index['data']);
			if ($index['data']['type'] == 'FULLTEXT') $hasFulltextIndex = true;
		}
		
		// create table
		$sql = "CREATE TABLE ".$tableName." (
				".$columnDefinition."
				".(!empty($indexDefinition) ? ',' : '')."
				".$indexDefinition."
			) ENGINE=".($hasFulltextIndex ? 'MyISAM' : 'InnoDB')." DEFAULT CHARSET=utf8";
		$statement = $this->dbObj->prepareStatement($sql);
		$statement->execute();
	}
	
	/**
	 * @see DatabaseEditor::dropTable()
	 */
	public function dropTable($tableName) {
		$sql = "DROP TABLE IF EXISTS ".$tableName;
		$statement = $this->dbObj->prepareStatement($sql);
		$statement->execute();
	}
	
	/**
	 * @see DatabaseEditor::addColumn()
	 */
	public function addColumn($tableName, $columnName, $columnData) {
		$sql = "ALTER TABLE ".$tableName." ADD COLUMN ".$this->buildColumnDefinition($columnName, $columnData);
		$statement = $this->dbObj->prepareStatement($sql);
		$statement->execute();
	}
	
	/**
	 * @see DatabaseEditor::alterColumn()
	 */
	public function alterColumn($tableName, $oldColumnName, $newColumnName, $newColumnData) {
		$sql = "ALTER TABLE ".$tableName." CHANGE COLUMN ".$oldColumnName." ".$this->buildColumnDefinition($newColumnName, $newColumnData);
		$statement = $this->dbObj->prepareStatement($sql);
		$statement->execute();
	}
	
	/**
	 * @see DatabaseEditor::dropColumn()
	 */
	public function dropColumn($tableName, $columnName) {
		$sql = "ALTER TABLE ".$tableName." DROP COLUMN ".$columnName;
		$statement = $this->dbObj->prepareStatement($sql);
		$statement->execute();
	}
	
	/**
	 * @see DatabaseEditor::addIndex()
	 */
	public function addIndex($tableName, $indexName, $indexData) {
		$sql = "ALTER TABLE ".$tableName." ADD ".$this->buildIndexDefinition($indexName, $indexData);
		$statement = $this->dbObj->prepareStatement($sql);
		$statement->execute();
	}
	
	/**
	 * @see DatabaseEditor::addIndex()
	 */
	public function addForeignKey($tableName, $indexName, $indexData) {
		$sql = "ALTER TABLE ".$tableName." ADD";
		
		// add index name
		if (!empty($indexName)) $sql .= " CONSTRAINT ".$indexName;
		
		// add columns
		$sql .= " FOREIGN KEY (".str_replace(',', ',', preg_replace('/\s+/', '', $indexData['columns'])).")";
		
		// add referenced table name
		$sql .= " REFERENCES ".$indexData['referencedTable'];
		
		// add referenced columns
		$sql .= " (".str_replace(',', ',', preg_replace('/\s+/', '', $indexData['referencedColumns'])).")";
		
		// add operation and action
		if (!empty($indexData['operation'])) $sql .= " ON ".$indexData['operation']." ".$indexData['action'];
		
		$statement = $this->dbObj->prepareStatement($sql);
		$statement->execute();
	}
	
	/**
	 * @see DatabaseEditor::dropIndex()
	 */
	public function dropIndex($tableName, $indexName) {
		$sql = "ALTER TABLE ".$tableName." DROP INDEX ".$indexName;
		$statement = $this->dbObj->prepareStatement($sql);
		$statement->execute();
	}
	
	/**
	 * Builds a column definition for execution in a create table or alter table statement.
	 * 
	 * @param	string		$columnName
	 * @param	array		$columnData
	 * @param	string
	 */
	protected function buildColumnDefinition($columnName, $columnData) {
		// column name
		$definition = $columnName;
		// column type
		$definition .= " ".$columnData['type'];
		// column length and decimals
		if (!empty($columnData['length'])) {
			$definition .= "(".$columnData['length'].(!empty($columnData['decimals']) ? ",".$columnData['decimals'] : "").")";
		}
		// enum / set
		if ($columnData['type'] == 'enum' && !empty($columnData['values'])) {
			$definition .= "(".$columnData['values'].")";
		}
		// not null / null
		if (!empty($columnData['notNull'])) $definition .= " NOT NULL";
		// default
		if (isset($columnData['default']) && $columnData['default'] !== '') $definition .= " DEFAULT ".$columnData['default'];
		// auto_increment
		if (!empty($columnData['autoIncrement'])) $definition .= " AUTO_INCREMENT";
		// key
		if (!empty($columnData['key'])) $definition .= " ".$columnData['key']." KEY";
		
		return $definition;
	}
	
	/**
	 * Builds a index definition for execution in a create table or alter table statement.
	 * 
	 * @param	string		$indexName
	 * @param	array		$indexData
	 * @param	string
	 */
	protected function buildIndexDefinition($indexName, $indexData) {
		$definition = "";
		// index type
		if ($indexData['type'] == 'PRIMARY') $definition = "PRIMARY KEY";
		else if ($indexData['type'] == 'UNIQUE') $definition = "UNIQUE KEY";
		else if ($indexData['type'] == 'FULLTEXT') $definition = "FULLTEXT KEY";
		else $definition = "KEY";
		
		// index name
		if (!empty($indexName)) $definition .= " ".$indexName."";
		// columns
		$definition .= " (".str_replace(',', ',', preg_replace('/\s+/', '', $indexData['columns'])).")";
		
		return $definition;
	}
}
