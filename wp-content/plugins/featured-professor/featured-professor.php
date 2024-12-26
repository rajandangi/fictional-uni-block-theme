<?php

/*
  Plugin Name: Featured Professor Block Type
  Version: 1.0
  Author: Rajan Dangi
  Author URI: https://www.rajan.com.np
  Text Domain: featured-professor
  Domain Path: /languages
*/

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

// Include files from inc folder
require_once plugin_dir_path(__FILE__) . '/inc/generateProfessorHTML.php';
require_once plugin_dir_path(__FILE__) . '/inc/relatedPostsHTML.php';

class FeaturedProfessor
{
    function __construct()
    {
        add_action('init', [$this, 'onInit']);
        add_action('rest_api_init', [$this, 'profHTML']);
        add_filter('the_content', [$this, 'addRelatedPosts']);
    }

    function addRelatedPosts($content)
    {
        if (is_singular('professor') && in_the_loop() && is_main_query()) {
            return $content . relatedPostsHTML(get_the_ID());
        }
        return $content;
    }

    function profHTML()
    {
        register_rest_route(
            'featuredProfessor/v1',
            'getHTML',
            array(
                'methods' => WP_REST_SERVER::READABLE,
                'callback' => [$this, 'getProfHTML'],
                'permission_callback' => '__return_true'
            )
        );
    }

    function getProfHTML($data)
    {
        return generateProfessorHTML($data['profID']);
    }

    function onInit()
    {
        // Load text domain for translations
        load_plugin_textdomain('featured-professor', false, dirname(plugin_basename(__FILE__)) . '/languages');

        // Register meta field to use in Gutenberg editor REST API
        register_meta(
            'post',
            'featuredprofessor',
            array(
                'show_in_rest' => true,
                'type' => 'number',
                'single' => false,
            )
        );

        wp_register_script('featuredProfessorScript', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-i18n', 'wp-editor'));
        wp_register_style('featuredProfessorStyle', plugin_dir_url(__FILE__) . 'build/index.css');

        // Load translations for javascript
        wp_set_script_translations('featuredProfessorScript', 'featured-professor', plugin_dir_path(__FILE__) . '/languages');

        register_block_type(
            'ourplugin/featured-professor',
            array(
                'render_callback' => [$this, 'renderCallback'],
                'editor_script' => 'featuredProfessorScript',
                'editor_style' => 'featuredProfessorStyle'
            )
        );
    }

    function renderCallback($attributes)
    {
        if ($attributes['profID']) {
            wp_enqueue_style('featuredProfessorStyle');
            return generateProfessorHTML($attributes['profID']);
        }
        return null;
    }
}

$featuredProfessor = new FeaturedProfessor();
