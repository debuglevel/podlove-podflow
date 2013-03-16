<?php

namespace Podlove\Modules\Podflow\Actions;

use Podlove\Modules\Podflow\Lib\Logger;

include dirname(__FILE__) . '/../lib/guzzle.phar';

class Podlove_Publish_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    private function add_post($title)
    {
        $post = array(
            'ID' => NULL,
            'post_content' => \Podlove\Podcast_Post_Type::$default_post_content, // TODO: might better be replaced by a template
            'post_title' => $title,
            'post_type' => 'podcast',
        );

        $post_id = wp_insert_post($post);
        return get_post($post_id);
    }

    private function add_episode($post, $subtitle, $summary, $slug, $duration)
    {
        $episode = \Podlove\Model\Episode::find_or_create_by_post_id($post->ID);

        $episode->subtitle = $subtitle;
        $episode->summary = $summary;
        $episode->duration = $duration;
        $episode->slug = $slug;

        $episode->save();

        return $episode;
    }

    private function add_mediafile($episode)
    {
        $episode_assets = \Podlove\Model\EpisodeAsset::all();

        foreach ($episode_assets as $episode_asset)
        {
            Logger::log('Checking if there is a '.$episode_asset->title.' file.');
            $mediafile = \Podlove\Model\MediaFile::find_or_create_by_episode_id_and_episode_asset_id($episode->id,
                            $episode_asset->id);
            $mediafile->determine_file_size();

            if ($mediafile->size > 0)
            {
                Logger::log('Determined a file size (bigger than 0) of <strong>'.$mediafile->size.'</strong> for Episode Asset <strong>'.$mediafile->episode_asset_id.'</strong> and therefore saving it.');
                $mediafile->save();
            }
            else
            {
                Logger::log('Determined a file size of <strong>'.$mediafile->size.'</strong> for Episode Asset <strong>'.$mediafile->episode_asset_id.'</strong> and therefore deleting it.');
                $mediafile->delete();
            }
        }
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        $title = $execution->getVariable('episode_title');
        $subtitle = $execution->getVariable('episode_subtitle');
        $summary = $execution->getVariable('episode_summary');
        $duration = $execution->getVariable('episode_duration');
        $slug = $execution->getVariable('episode_slug');

        $post = $this->add_post($title);
        $episode = $this->add_episode($post, $subtitle, $summary, $slug,
                $duration);
        $mediafile = $this->add_mediafile($episode);

        $publish_state = $execution->getVariable('episode_publish_state');
        if ($publish_state == 'publish')
        {
            wp_publish_post($post->ID);
        }
        else if (($publish_state == 'draft' or true===true))    //XXX: in the future might be 'scheduled' added. for now just save everything as a draft
        {
            
        }
        

        return true;
    }

    public function __toString()
    {
        return "Podlove_Publish_Service_Object";
    }

}
