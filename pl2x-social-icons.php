<?php
/**
 * Plugin Name: PL2X Social Icons
 * Description: Add Simple Social icons
 * Author: Evan Mattson (@aaemnnosttv)
 * Author URI: https://aaemnnost.tv
 * Version: 2.0
 * 
 * GitHub Plugin URI: PL2X/pl2x-social-icons
 */

namespace PL2X;

class SocialIcons
{
	const version = '2.0';

	public function __construct()
	{
		$this->uri = plugins_url('', __FILE__);
		$this->dir = plugin_dir_path( __FILE__ );
	}

	public function hooks()
	{
		add_action('init', array($this, 'init'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue'));
		add_action('pagelines_setup', array($this, 'add_social_options'));
	}

	public function init()
	{
		wp_register_style('social-icons', "{$this->uri}/social-icons.css" , array(), self::version);
		add_shortcode('social_icons', array($this, 'shortcode'));
	}

	public function add_social_options()
	{
		$options = array(
			'social_profiles' => array(
				'title'        => 'Social Profiles',
				'type'         => 'multi_option',
				'selectvalues' => $this->get_options()
			),
		);

		pl_add_options_page( array(
			'name'		=> 'Social_Icons/Profiles',
			'array'		=> $options,
		) );
	}

	public function enqueue()
	{
		wp_enqueue_style('social-icons');
	}

	public function shortcode( $atts, $content, $tag )
	{
		$data = array();
		
		foreach ( $this->get_options() as $key => $o )
		{
			$value = ploption( $key );

			if ( $value )
				$data[ $key ] = sprintf( $o['format'], $value );
		}
		$data = array_filter( $data );

		if ( empty( $data ) )
			return '';
		else
		{
			$icons = implode("\n", $data);
			return "<div class='social-icons'>$icons</div>";
		}
	}

	public function get_options()
	{
		return array(
					'facebook_id' => array(
						'inputlabel' => 'Facebook ID',
						'type'       => 'text',
						'format'     => '<a class="social-facebook" target="_blank" href="http://www.facebook.com/%s"></a>',
					),
					'twitter_handle' => array(
						'inputlabel' => 'Twitter Handle (@_____)',
						'type'       => 'text',
						'format'     => '<a class="social-twitter" target="_blank" href="http://www.twitter.com/%s"></a>',
					),
					'google_id' => array(
						'inputlabel' => 'Google + ID',
						'type'       => 'text',
						'format'     => '<a class="social-google" target="_blank" href="http://plus.google.com/+%s"></a>',
					),
					'linkedin_url' => array(
						'inputlabel' => 'Linkedin Profile URL',
						'type'       => 'text',
						'format'     => '<a class="social-linkedin" target="_blank" href="%s"></a>',
					),
					'pinterest_handle' => array(
						'inputlabel' => 'Pinterest Handle',
						'type'       => 'text',
						'format'     => '<a class="social-pinterest" target="_blank" href="http://www.pinterest.com/%s"></a>',
					),
					'yelp_id' => array(
						'inputlabel' => 'Yelp biz ID',
						'type'       => 'text',
						'format'     => '<a class="social-yelp" target="_blank" href="http://www.yelp.com/biz/%s"></a>',
					),
				);
	}

} // SocialIcons

function pl2x_plugin_social_icons()
{
	static $instance;

	if ( ! $instance) {
		$instance = new SocialIcons();
	}

	return $instance;
}

add_action('plugins_loaded', 'pl2x_plugin_social_icons');
