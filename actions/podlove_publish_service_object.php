<?php
namespace Podlove\Modules\Podflow\Actions;

include dirname(__FILE__) . '/../lib/guzzle.phar';

class Podlove_Publish_Service_Object implements \ezcWorkflowServiceObject {
	public function __construct() {
	}

	public function execute(\ezcWorkflowExecution $execution) {
		$execution_id = $execution -> getVariable('execution_id');

		$title = $execution -> getVariable('episode_title');
		$subtitle = $execution -> getVariable('episode_subtitle');
		$summary = $execution -> getVariable('episode_summary');
		$duration = $execution -> getVariable('episode_duration');
		$slug = $execution -> getVariable('episode_slug');

		$post = array(
			'ID'             => NULL,
			'post_content'   => "[podlove-web-player]\n\n[podlove-episode-downloads]",	//TODO: insert default podlove template
			'post_title'     => $title,
			'post_type'      => 'podcast',
		);
		
		$post_id = wp_insert_post($post);
		$episode = \Podlove\Model\Episode::find_or_create_by_post_id($post_id);
		
		$episode->subtitle = $subtitle;
		$episode->summary = $summary;
		$episode->duration = $duration;
		$episode->slug = $slug;
		
		$episode->save();
		
		wp_publish_post($post_id);
		
		return true;
	}

	public function __toString() {
		return "Podlove_Publish_Service_Object";
	}

}
