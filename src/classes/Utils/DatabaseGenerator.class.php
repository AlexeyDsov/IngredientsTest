<?php
	class DatabaseGenerator
	{
		/**
		 * @var DBSchema
		 */
		private $schema = null;
		/**
		 * @var DB
		 */
		private $db = null;

		/**
		 * @return DatabaseGenerator
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 *
		 * @param string $schemaPath
		 * @return DatabaseGenerator 
		 */
		public function setSchemaPath($schemaPath)
		{
			include $schemaPath;
			Assert::isTrue(isset($schema), 'wrong schemaPath');
			Assert::isInstance($schema, 'DBSchema', 'wrong schemaPath');

			$this->schema = $schema;

			return $this;
		}

		/**
		 * @param string $dbName
		 * @return DatabaseGenerator 
		 */
		public function setDBName($dbName)
		{
			$this->db = DBPool::me()->getLink($dbName);
			return $this;
		}

		/**
		 * @return DatabaseGenerator 
		 */
		public function run()
		{
			Assert::isInstance($this->db, 'DB', 'call setDBName first');
			Assert::isInstance($this->schema, 'DBSchema', 'call setSchemaPath first');

			$this->dropAllTables();
			$this->createAllTables();

			return $this;
		}

		/**
		 * @return DatabaseGenerator 
		 */
		private function dropAllTables()
		{
			foreach ($this->schema->getTableNames() as $name) {
				try {
					$this->db->queryRaw(
						OSQL::dropTable($name, true)->toDialectString(
							$this->db->getDialect()
						)
					);
				} catch (DatabaseException $e) {
					if (
						mb_strpos($e->getMessage(), 'does not exist') === false
						&& mb_strpos($e->getMessage(), 'missing database') === false
					) {
						throw $e;
					}
				}

				try {
					if ($this->db->hasSequences()) {
						foreach (
							$this->schema->getTableByName($name)->getColumns()
								as $columnName => $column
						) {
							if ($column->isAutoincrement()) {
								$this->db->queryRaw("DROP SEQUENCE {$name}_id;");
							}
						}
					}
				} catch (DatabaseException $e) {
					if (mb_strpos($e->getMessage(), 'does not exist') === false) {
						throw $e;
					}
				}
			}

			return $this;
		}

		/**
		 * @return DatabaseGenerator 
		 */
		private function createAllTables()
		{
			$this->db->begin();
			foreach ($this->schema->getTables() as $tableName => $table) {
				/* @var $table DBTable */
				$this->db->queryRaw($table->toDialectString($this->db->getDialect()));
			}
			$this->db->commit();

			return $this;
		}
	}
