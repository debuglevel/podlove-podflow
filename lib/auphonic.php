<?php

namespace Podlove\Modules\Podflow\Lib;

class Auphonic
{

    public function get_username()
    {
        return get_option('podflow_auphonic_username', null);
    }

    public function get_password()
    {
        return get_option('podflow_auphonic_password', null);
    }

    public function get_rest_api_client()
    {
        return new \Guzzle\Http\Client('https://auphonic.com/api/',
                array('ssl.certificate_authority' => false));
        //XXX: ignoring ssl certs is a rather bad idea
    }
    
    public function get_rest_simple_api_client()
    {
        return new \Guzzle\Http\Client('https://auphonic.com/api/simple/',
                array('ssl.certificate_authority' => false));
        //XXX: ignoring ssl certs is a rather bad idea
    }

    public function start_production($filepath, $title, $preset, $username,
            $password)
    {
        $client = Auphonic::get_rest_simple_api_client();

        $request = $client->post('productions.json', null,
                        array('preset' => $preset, 'title' => $title, 'action' => 'start',
                    'input_file' => '@' . $filepath))->setAuth($username,
                $password);
        
        $response = $request->send();

        return $response->json();
    }

    public function get_production_uuid($productioninfo)
    {
        return $productioninfo['data']['uuid'];
    }

    public function get_productioninfo($uuid)
    {
        $client = Auphonic::get_rest_api_client();

        $request = $client->get('production/'.$uuid.'.json')
                ->setAuth(Auphonic::get_username(), Auphonic::get_password());

        $response = $request->send();

        return $response->json();
    }
    
    public function get_presets()
    {
        $client = Auphonic::get_rest_api_client();
        
        $request = $client->get('presets.json')
                ->setAuth(Auphonic::get_username(), Auphonic::get_password());

        $response = $request->send();
        $array = $request->send()->json();
             
        $presets = $array['data'];
        return $presets;
    }
    
    public function get_presets_simple()
    {
        $raw_presets = Auphonic::get_presets();
        
        $presets = array();
        foreach ($raw_presets as $raw_preset)
        {        
            $sub = array();
            $sub['uuid'] = $raw_preset['uuid'];
            $sub['name'] = $raw_preset['preset_name'];
            $presets[] = $sub;
        }
              
        return $presets;
    }

    public function get_duration($productioninfo)
    {
        return $productioninfo['data']['length_timestring'];
    }

    public function get_basename($productioninfo)
    {
        return $productioninfo['data']['output_basename'];
    }

    public function get_download_url($outputfile)
    {
        return $outputfile['download_url'];
    }

    public function get_filename($outputfile)
    {
        return $outputfile['filename'];
    }

    public function get_status($productioninfo)
    {
        return $productioninfo['data']['status_string'];
    }

}

?>
