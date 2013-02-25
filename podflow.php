<?php

namespace Podlove\Modules\Podflow;

use \Podlove\Modules\Podflow\Lib\Workflow;
use \Podlove\Modules\Podflow\Lib\Workflow_Execution;
use \Podlove\Modules\Podflow\Lib\Logger;
use \Podlove\Modules\Podflow\Lib\Form;

spl_autoload_register(__NAMESPACE__ . '\Lib\Autoloader_Logger::log');

require_once "lib/zetacomponents/Base/src/base.php";
spl_autoload_register(array('ezcBase', 'autoload'));

class Podflow extends \Podlove\Modules\Base
{

    protected $module_name = 'Podflow';
    protected $module_description = 'Provides a wizard and workflow engine to automate publishing episodes.';

    const menu_slug_new = 'podlove_podflow_new';
    const menu_slug_list = 'podlove_podflow_list';

    public function load()
    {
        add_action('admin_menu', array($this, 'create_menu'));
    }

    public function create_menu()
    {
        // // create new top-level menu
        // add_options_page('Podlove Podflow Options', 'Podlove Podflow', 'administrator', __FILE__, array($this, 'settings_page'));

        add_submenu_page(
                /* $parent_slug */'edit.php?post_type=podcast',
                /* $page_title */ 'Podflow',
                /* $menu_title */
                'Podflow Assistant',
                /* $capability */
                'administrator',
                /* $menu_slug  */
                Podflow::menu_slug_new,
                /* $function   */ array($this, 'start_or_continue_workflow'));

        add_submenu_page(
                /* $parent_slug */'edit.php?post_type=podcast',
                /* $page_title */ 'Podflow',
                /* $menu_title */
                'Podflow Executions',
                /* $capability */
                'administrator',
                /* $menu_slug  */
                Podflow::menu_slug_list,
                /* $function   */ array($this, 'list_workflows'));
    }

    public function list_workflows()
    {
        $workflow_executions = Workflow_Execution::get_all_workflow_executions_info();
        
        $form_vars = array('workflow_executions' => $workflow_executions);
        Form::show_form('workflow_executions_list.php', $form_vars);
    }

    public function start_or_continue_workflow()
    {
        $execution_id = $_REQUEST['execution_id'];
        if (isset($_REQUEST['execution_id']) and is_numeric($execution_id))
        {
            $this->continue_workflow((int) $execution_id);
        }
        else
        {
            $this->start_workflow();
        }
    }

    private function continue_workflow($execution_id)
    {
        $execution = Workflow_Execution::get_workflow_execution($execution_id);

        $execution->resume();
    }

    private function start_workflow()
    {
        $execution = Workflow_Execution::create_workflow_execution(Workflows\Auphonic_Workflow_Builder::name);

        $execution_id = $execution->start();
        // workaround to let the workflow know its own execution id
        $execution->setVariable('execution_id', $execution_id);
        $execution->resume();
    }

}
