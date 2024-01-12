<?php
/*
Plugin Name: Our First Unique Plugin
Plugin URI: https://www.rajandangi.com.np
Description: This is our first unique plugin
Author: Rajan Dangi
Version: 1.0
Author URI: https://www.rajandangi.com.np
Text Domain: wcdomain
Domain Path: /languages
*/
class WordCountAndTimePlugin
{
    function __construct()
    {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
        add_action('init', array($this, 'languages'));
    }

    function languages()
    {
        load_plugin_textdomain('wcdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    function settings()
    {
        add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');

        // Select
        add_settings_field('wcp_location', esc_html__('Display Location', 'wcdomain'), array($this, 'locationHtml'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_location', array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => '0'));

        // Text Field
        add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHtml'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));

        // Checkbox
        add_settings_field('wcp_wordcount', 'Word Count', array($this, 'checkboxHtml'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_wordcount'));
        register_setting('wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        add_settings_field('wcp_charactercount', 'Character Count', array($this, 'checkboxHtml'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_charactercount'));
        register_setting('wordcountplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        add_settings_field('wcp_readtime', 'Read Time', array($this, 'checkboxHtml'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_readtime'));
        register_setting('wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
    }

    // Sanitize the select input i.e. location value validation
    function sanitizeLocation($input)
    {
        if ($input != 0 && $input != 1) {
            add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be beginning or end.');
            return get_option('wcp_location');
        }
        return $input;
    }

    // Handle the select input HTML
    function locationHtml()
    { ?>
        <select name="wcp_location">
            <option value="0" <?php selected(esc_attr(get_option('wcp_location')), 0); ?>>Beginning of Post</option>
            <option value="1" <?php selected(esc_attr(get_option('wcp_location')), 1); ?>>End of Post</option>
        </select>
    <?php }
    // Handle the text input HTML
    function headlineHtml()
    { ?>
        <input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')); ?>">
    <?php }
    // Handle the checkbox input HTML
    function checkboxHtml($args)
    { ?>
        <input type="checkbox" name="<?php echo $args['theName']; ?>" value="1" <?php checked(esc_attr(get_option($args['theName'])), 1); ?>>
    <?php }

    function adminPage()
    {
        add_options_page('Word Count Settings', __('Word Count', 'wcdomain'), 'manage_options', 'word-count-settings-page', array($this, 'ourHtml'));
    }

    function ourHTML()
    { ?>
        <div class="wrap">
            <h1>Word Count Settings</h1>
            <form action="options.php" method="POST">
                <?php
                settings_fields('wordcountplugin');
                do_settings_sections('word-count-settings-page');
                submit_button();
                ?>
            </form>
        </div>
    <?php }

}
$wordCountAndTimePlugin = new WordCountAndTimePlugin();



// Implement Word Count and Time Plugin on the front end of the website using the_content filter
require_once plugin_dir_path(__FILE__) . 'inc/word-count.php';

