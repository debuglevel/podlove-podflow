<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Storage;
use \Podlove\Modules\Podflow\Lib\Logger;

class Episode_Upload_Receiver_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        if (isset($_FILES['episodefile']))
        {
            // TODO: file should be deleted after uploading to auphonic
            $uploadinfo = Storage::get_temporary_upload_information();

            $filename = $_FILES['episodefile']['name'];
            $target_path = $uploadinfo['path'] . '/' . $filename;
            $target_url = $uploadinfo['url'] . '/' . $filename;

            move_uploaded_file($_FILES['episodefile']['tmp_name'], $target_path);

            $execution->setVariable('episode_temp_path', $target_path);
            $execution->setVariable('episode_temp_url', $target_url);

            Logger::log('I received a file called <strong>' . $_FILES['episodefile']['name'] . '</strong> and moved it to <strong>' . $target_path . '</strong> and it\'s accessible via <strong>' . $target_url . '</strong>');
            return true;
        }
        else
        {
            Logger::log('I received no file.');
            return false;
        }
    }

    public function __toString()
    {
        return __CLASS__;
    }

}
