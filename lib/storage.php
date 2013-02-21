<?php

namespace Podlove\Modules\Podflow\Lib;

class Storage
{
    public function get_permanent_directory()
    {
        return '/opt/lampp/htdocs/podlovemedia/';   // TODO: convert to option
    }
    
    public function get_temporary_upload_information()
    {
        return wp_upload_dir();
    }
}

?>
