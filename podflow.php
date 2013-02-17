<?php
namespace Podlove\Modules\Podflow;

class AutoloaderLogger {
	static public function log($name) {
		//print '[[' . $name . "]]";
	}

}

spl_autoload_register(__NAMESPACE__ . '\AutoloaderLogger::log');
// As of PHP 5.3.0

require_once "lib/zetacomponents/Base/src/base.php";
spl_autoload_register(array('ezcBase', 'autoload'));

class Podflow extends \Podlove\Modules\Base {
	protected $module_name = 'Podflow';
	protected $module_description = 'Targets to automate the process of publishing a podcast to be as easy as possible.';

	public function load() {
		add_action('admin_menu', array($this, 'create_menu'));
	}

	public function create_menu() {
		// // create new top-level menu
		// add_options_page('Podlove Podflow Options', 'Podlove Podflow', 'administrator', __FILE__, array($this, 'settings_page'));

		//add_posts_page('My Plugin Posts Page', 'My Plugin', 'read', 'my-unique-identifier', 'my_plugin_function');
		add_submenu_page(
		/* $parent_slug*/'edit.php?post_type=podcast',
		/* $page_title */'Podflow',
		/* $menu_title */'Start a Podflow',
		/* $capability */'administrator',
		/* $menu_slug  */'podlove_podflow_settings_handle',
		/* $function   */array($this, 'start_or_continue_workflow'));
	}

	private function get_prefix() {
		return "wp_podlove_podflow_";
	}

	private function create_tables_if_necessary($dbHandler, $schema) {
		// check if tables exist
		// not very sophisticated, but should work:
		// - check whether the first table in the schema exists.
		// - if not: create them all
		$innerSchema = &$schema -> getSchema();
		$keys = array_keys($innerSchema);
		$firstTable = $keys[0];

		global $wpdb;

		if ($wpdb -> get_var("SHOW TABLES LIKE '$firstTable'") != $firstTable) {
			$schema -> writeToDb($dbHandler);
		}
	}

	private function prefix_tables($schema) {
		$innerSchema = &$schema -> getSchema();

		$keys = array_keys($innerSchema);
		foreach ($keys as $key) {
			$innerSchema[$this -> get_prefix() . $key] = $innerSchema[$key];
			unset($innerSchema[$key]);
		}
	}

	private function startsWith($haystack, $needle) {
		return !strncmp($haystack, $needle, strlen($needle));
	}

	private function remove_foreign_tables($schema) {
		$innerSchema = &$schema -> getSchema();

		$prefix = $this -> get_prefix();

		$keys = array_keys($innerSchema);
		foreach ($keys as $key) {
			if ($this -> startsWith($key, $prefix) == false) {
				unset($innerSchema[$key]);
			}
		}
	}

	private function setup_tables($dbHandler) {
		$definedSchema = \ezcDbSchema::createFromFile('array', dirname(__FILE__) . '/lib/zetacomponents/WorkflowDatabaseTiein/tests/workflow.dba');
		$this -> prefix_tables($definedSchema);

		// XXX: does not work they way expected: there is a diff return even if the tables were just created.
		// $currentSchema = \ezcDbSchema::createFromDb($dbHandler);
		// $this->remove_foreign_tables($currentSchema);
		//
		// $diff = \ezcDbSchemaComparator::compareSchemas($currentSchema, $definedSchema);
		// $diff->applyToDb($dbHandler);

		$this -> create_tables_if_necessary($dbHandler, $definedSchema);
	}
	
	private function setup_workflows($dbHandler)
	{
		$definitionStorage = new \ezcWorkflowDatabaseDefinitionStorage($dbHandler);
		$definitionStorage->options['prefix'] = $this->get_prefix();
		
		$auphonic_workflow_builder = new Workflows\Auphonic_Workflow_Builder();
		$auphonic_workflow_definition = $auphonic_workflow_builder -> build_workflow();
		$definitionStorage->save($auphonic_workflow_definition);
	}

	//TODO: replace direct DatabaseHandler by a wrapper around the $wpdb object
	private function get_database_handler() {
		$dbParams = array('database' => DB_NAME, 'username' => DB_USER, 'password' => DB_PASSWORD, 'host' => DB_HOST, 'charset' => DB_CHARSET);
		$dbHandler = new \ezcDbHandlerMysql($dbParams);

		return $dbHandler;
	}

	private function get_workflow($workflow_name, $dbHandler) {
		// Set up workflow definition storage (database).
		$definitionStorage = new \ezcWorkflowDatabaseDefinitionStorage($dbHandler);
		$definitionStorage->options['prefix'] = $this->get_prefix();
		
		$workflow = $definitionStorage -> loadByName($workflow_name);
		return $workflow;
	}

	private function get_workflow_execution($workflow_name, $execution_id = null) {
		$dbHandler = $this -> get_database_handler();

		$options = new \ezcWorkflowDatabaseOptions;
		$options -> prefix = $this -> get_prefix();

		$execution = new \ezcWorkflowDatabaseExecution($dbHandler, $execution_id, $options);

		$this -> setup_tables($dbHandler); //TODO:
		$this -> setup_workflows($dbHandler); //TODO: does not logically belong here. should really be moved to a installation hook 

		if ($execution_id == null) {
			$execution -> workflow = $this -> get_workflow($workflow_name, $dbHandler);
		}

		return $execution;
	}

	public function start_or_continue_workflow() {
		$execution_id = $_REQUEST['execution_id'];
		if (isset($_REQUEST['execution_id']) and is_numeric($execution_id)) {
			$this -> continue_workflow((int)$execution_id);
		} else {
			$this -> start_workflow();
		}
	}

	private function continue_workflow($execution_id) {
		$execution = $this -> get_workflow_execution($workflow_definition, $execution_id);

		$execution -> resume();
	}

	private function start_workflow() {
		$execution = $this -> get_workflow_execution('Auphonic');

		$execution_id = $execution -> start();
		// workaround to let the workflow know its own execution id
		$execution -> setVariable('execution_id', $execution_id);
		$execution -> resume();
	}

	// public function settings_page() {
	// echo "oh hai, this is a settings page";
	// }

}
