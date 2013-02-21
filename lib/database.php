<?php

namespace Podlove\Modules\Podflow\Lib;

class Database
{

    public function get_table_prefix()
    {
        return "wp_podlove_podflow_";
    }

    private function create_tables_if_necessary($dbHandler, $schema)
    {
        // check if tables exist
        // not very sophisticated, but should work:
        // - check whether the first table in the schema exists.
        // - if not: create them all
        $innerSchema = &$schema->getSchema();
        $keys = array_keys($innerSchema);
        $firstTable = $keys[0];

        global $wpdb;

        if ($wpdb->get_var("SHOW TABLES LIKE '$firstTable'") != $firstTable)
        {
            $schema->writeToDb($dbHandler);
        }
    }

    private function prefix_tables($schema)
    {
        $innerSchema = &$schema->getSchema();

        $keys = array_keys($innerSchema);
        foreach ($keys as $key)
        {
            $innerSchema[Database::get_table_prefix() . $key] = $innerSchema[$key];
            unset($innerSchema[$key]);
        }
    }

    private function remove_foreign_tables($schema)
    {
        $innerSchema = &$schema->getSchema();

        $prefix = $this->get_table_prefix();

        $keys = array_keys($innerSchema);
        foreach ($keys as $key)
        {
            if ($this->startsWith($key, $prefix) == false)
            {
                unset($innerSchema[$key]);
            }
        }
    }

    public function setup_tables($dbHandler)
    {
        $definedSchema = \ezcDbSchema::createFromFile('array',
                        dirname(__FILE__) . '/zetacomponents/WorkflowDatabaseTiein/tests/workflow.dba');
        Database::prefix_tables($definedSchema);

        // XXX: does not work they way expected: there is a diff return even if the tables were just created.
        // $currentSchema = \ezcDbSchema::createFromDb($dbHandler);
        // $this->remove_foreign_tables($currentSchema);
        //
		// $diff = \ezcDbSchemaComparator::compareSchemas($currentSchema, $definedSchema);
        // $diff->applyToDb($dbHandler);

        Database::create_tables_if_necessary($dbHandler, $definedSchema);
    }

    //TODO: replace direct DatabaseHandler by a wrapper around the $wpdb object
    public function get_database_handler()
    {
        $dbParams = array('database' => DB_NAME, 'username' => DB_USER, 'password' => DB_PASSWORD,
            'host' => DB_HOST, 'charset' => DB_CHARSET);
        $dbHandler = new \ezcDbHandlerMysql($dbParams);

        return $dbHandler;
    }

}

?>
