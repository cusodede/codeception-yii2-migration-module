<?php
declare(strict_types=1);

namespace dspl\tools\traits;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;

/**
 * trait traits\MigrationTrait
 */
trait MigrationTrait
{
    /**
     * @param string $excludes
     * @return int|void
     * @throws Exception
     */
    public function actionClearTables(string $excludes = "")
    {
        /**
         * @var Controller $this
         */

        if (YII_ENV_PROD) {
            $this->stdout("YII_ENV is set to 'prod'.\nClear tables is not possible on production systems.\n");
            return ExitCode::OK;
        }

        $excludeTables = explode(",", $excludes);
        $db = $this->db;
        $schemas = $db->schema->getTableSchemas();
        foreach ($schemas as $schema) {
            $schemaName = $schema->name;
            if (true === in_array($schemaName, $excludeTables, true)) {
                $this->stdout("{$schemaName} in exclude tables. Skip....\n");
                continue;
            }
            $this->stdout("Delete from table {$schemaName}.\n");
            $db->createCommand("DELETE FROM $schemaName;")->execute();
        }
    }

    /**
     * @return int|void
     * @throws Exception
     */
    public function actionDrop()
    {
        /**
         * @var Controller $this
         */

        if (YII_ENV_PROD) {
            $this->stdout("YII_ENV is set to 'prod'.\nClear tables is not possible on production systems.\n");
            return ExitCode::OK;
        }

        $db = $this->db;
        $schemas = $db->schema->getTableSchemas();

        foreach ($schemas as $schema) {
            $schemaName = $schema->name;
            $this->stdout("Drop table {$schemaName}.\n");
            $db->createCommand("DROP TABLE {$schemaName} CASCADE;")->execute();
        }
    }
}