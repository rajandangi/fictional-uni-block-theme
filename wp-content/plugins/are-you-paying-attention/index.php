<?php
/*
Plugin Name: Are You Paying Attention Quiz
Plugin URI: https://www.rajandangi.com.np
Description: Give your readers a multiple choice question.
Author: Rajan Dangi
Version: 1.0
Author URI: https://www.rajandangi.com.np
Text Domain: aypadomain
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class AreYouPayingAttention
{
    function __construct()
    {
        add_action('init', array($this, 'adminAssets'));
    }


    function adminAssets()
    {
        register_block_type(
            __DIR__,
            array(
                'render_callback' => array($this, 'theHTML')
            )
        );
    }

    function theHTML($attributes)
    {
        // Only load js and css on the frontend if the block is present
        // if (!is_admin()) {
        //     wp_enqueue_script('attentionFrontend', plugin_dir_url(__FILE__) . 'build/frontend.js', array('wp-element'), '1.0', true);
        // }

        ob_start(); ?>
        <div class="paying-attention-update-me">
            <pre style="display:none"> <?php echo wp_json_encode($attributes) ?></pre>
        </div>
        <?php
        return ob_get_clean();
    }
}
$areyoupayingattention = new AreYouPayingAttention();