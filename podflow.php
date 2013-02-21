<?php

namespace Podlove\Modules\Podflow;

use \Podlove\Modules\Podflow\Lib\Workflow;
use \Podlove\Modules\Podflow\Lib\Logger;

spl_autoload_register(__NAMESPACE__ . '\Lib\Autoloader_Logger::log');

require_once "lib/zetacomponents/Base/src/base.php";
spl_autoload_register(array('ezcBase', 'autoload'));

class Podflow extends \Podlove\Modules\Base
{

    protected $module_name = 'Podflow';
    protected $module_description = 'Provides a wizard and workflow engine to automate publishing episodes.';

    const menu_slug = 'podlove_podflow';
    
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
                /* $menu_title */ 'Podflow Assistant',
                /* $capability */ 'administrator',
                /* $menu_slug  */ Podflow::menu_slug,
                /* $function   */ array($this, 'start_or_continue_workflow'));
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
        $execution = Workflow::get_workflow_execution($execution_id);

        $execution->resume();
    }

    private function start_workflow()
    {
        $execution = Workflow::create_workflow_execution('Auphonic');

        $execution_id = $execution->start();
        // workaround to let the workflow know its own execution id
        $execution->setVariable('execution_id', $execution_id);
        $execution->resume();
    }

}
