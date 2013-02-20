<?php

namespace Podlove\Modules\Podflow\Actions;

include dirname(__FILE__) . '/../lib/guzzle.phar';

class Auphonic_Finish_Check_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        $execution_id = $execution->getVariable('execution_id');
        $uuid = $execution->getVariable('episode_auphonic_uuid');

        $username = get_option('podflow_auphonic_username', null);
        $password = get_option('podflow_auphonic_password', null);

        $client = new \Guzzle\Http\Client('https://auphonic.com/api',
                array('ssl.certificate_authority' => false));
        //XXX: ignoring ssl certs is a rather bad idea

        $request = $client->get('production/' . $uuid . '.json')->setAuth($username,
                $password);
        sleep(15); //just for testing purposes

        $response = $request->send();

        $data = $response->json();
        $status = $data['data']['status_string'];

        if ($status == 'Done')
        {
            echo "<p>Debug: Auphonic is done producing the episode.</p>";
            return true;
        }
        else
        {
            echo "<p>Debug: Auphonic is not done yet producing the episode.</p>";
            //return false;	//just for debugging commented
            return true;
        }
    }

    public function __toString()
    {
        return "Auphonic_Finish_Check_Service_Object";
    }

}
