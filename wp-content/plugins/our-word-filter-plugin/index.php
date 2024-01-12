<?php
/*
Plugin Name: Our Word Filter Plugin
Plugin URI: https://www.rajandangi.com.np
Description: This is our word filter plugin
Author: Rajan Dangi
Version: 1.0
Author URI: https://www.rajandangi.com.np
Text Domain: wfdomain
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class OurWordFilterPlugin
{
    function __construct()
    {
        /**
         * Adds an action hook to the 'admin_menu' event, which calls the 'ourMenu' method of the current class.
         */
        add_action('admin_menu', array($this, 'ourMenu'));

        /**
         * Adds an action hook to initialize the plugin settings in the admin area.
         *
         * @since 1.0.0
         */
        add_action('admin_init', array($this, 'ourSettings'));

        /**
         * Adds a filter to the content of the WordPress posts.
         * This filter is responsible for applying the word filtering logic.
         *
         * @param string $content The content of the post.
         * @return string The filtered content.
         */
        if (get_option('plugin_words_to_filter')):
            add_action('the_content', array($this, 'filterLogic'));
        endif;
    }

    /**
     * Defines the ourSettings function.
     * Adds a settings section and field for filtered text replacement.
     */
    function ourSettings()
    {
        add_settings_section('replacemnet-text-section', null, null, 'word-filter-options');

        add_settings_field('replacement-text', 'Filtered Text', array($this, 'replacementTextHtml'), 'word-filter-options', 'replacemnet-text-section');
        register_setting('replacementFields', 'replacementText');
    }

    /**
     * Displays the HTML markup for the replacement text input field.
     *
     * This function outputs an HTML input field for the replacement text option in the plugin settings.
     * The value of the input field is set to the current value of the 'replacementText' option, also default value
     *
     * @since 1.0.0
     */
    function replacementTextHtml()
    {
        ?>
        <input type="text" name="replacementText" value="<?php echo esc_attr(get_option('replacementText', '***')); ?>">
        <p class="description">
            <?php echo esc_html__('Leave blank to simply remove the filtered words.', 'wfdomain'); ?>
        </p>
        <?php
    }

    /**
     * Applies word filtering logic to the given content.
     *
     * @param string $content The content to be filtered.
     * @return string The filtered content.
     */
    function filterLogic($content)
    {
        $badWords = explode(',', get_option('plugin_words_to_filter'));
        // remove whitespace from each word using array_map() and trim()
        $badWordsTrimmed = array_map('trim', $badWords);
        return str_ireplace($badWordsTrimmed, esc_html(get_option('replacementText', '****')), $content);
    }

    /**
     * Adds a menu page for the word filter plugin.
     *
     * @return void
     */
    function ourMenu()
    {
        // Add svg icon path
        /*
        add_menu_page(
            'Words to Filter', // page title
            'Word Filter', // menu title
            'manage_options', // capability
            'ourwordfilter', // menu slug
            array($this, 'wordFilterPage'), // callback function
            plugin_dir_url(__FILE__) . 'custom.svg', // icon url
            100, // position
        );
        */

        // Convert svg to base64 code and add it to menu directly
        $mainPageHook = add_menu_page(
            'Words to Filter', // page title
            'Word Filter', // menu title
            'manage_options', // capability
            'ourwordfilter', // menu slug
            array($this, 'wordFilterPage'), // callback function
            'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+Cg==', // goto console and type btoa(`svg code`) to get base64 code
            100, // position
        );
        add_submenu_page(
            'ourwordfilter', // parent slug
            'Words To Filter', // page title
            'Words List', // menu title
            'manage_options', // capability
            'ourwordfilter', // menu slug
            array($this, 'wordFilterPage'), // callback function
        );
        add_submenu_page(
            'ourwordfilter', // parent slug
            'Words Filter Option', // page title
            'Options', // menu title
            'manage_options', // capability
            'word-filter-options', // menu slug
            array($this, 'optionsSubPage'), // callback function
        );

        // Add assets to main page only (ourwordfilter)
        add_action("load-$mainPageHook", array($this, 'mainPageAssets'));
    }

    /**
     * Adds assets to the main page of the plugin.
     *
     * @return void
     */
    function mainPageAssets()
    {
        wp_enqueue_style('filterAdminCss', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    }

    /**
     * Handles the form submission.
     *
     * @return void
     */
    function handleForm()
    {
        if (isset($_POST['ourNonce']) && wp_verify_nonce($_POST['ourNonce'], 'saveFilterWords') && current_user_can('manage_options')) {
            update_option('plugin_words_to_filter', sanitize_text_field($_POST['plugin_words_to_filter']));
            ?>
            <div class="updated">
                <p>
                    <?php echo esc_html__('Your filtered words were saved.', 'wfdomain') ?>
                </p>
            </div>
        <?php } else { ?>
            <div class="error">
                <p>
                    <?php echo esc_html__('Sorry, you do not have permission to perform that action.', 'wfdomain') ?>
                </p>
            </div>
            <?php
        }
    }

    /**
     * Displays the word filter page.
     *  It uses custom HTML markup to render the form.
     *  It also handles the form submission using the custom handleForm method.
     * @return void
     */
    function wordFilterPage()
    {
        ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <?php
            if (isset($_POST['justsubmitted']) == "true") {
                $this->handleForm();
            }
            ?>
            <form method="post">
                <input type="hidden" name="justsubmitted" value="true">
                <?php wp_nonce_field('saveFilterWords', 'ourNonce'); ?>
                <label for="plugin_words_to_filter">
                    <p>Enter a <strong>comma-separated</strong> list of words to filter from your site's content</p>
                </label>
                <div class="word-filter__flex-container">
                    <textarea name="plugin_words_to_filter" id="plugin_words_to_filter"
                        placeholder="bad,mean,awful,horrible"><?php echo esc_textarea(get_option('plugin_words_to_filter')); ?></textarea>
                </div>
                <input type="submit" name="submit" id="submit" value="Save Changes" class="button button-primary">
            </form>
        </div>
        <?php
    }

    /**
     * Renders the options subpage for the Word Filter plugin.
     * It uses the WordPress Settings API to render the form.
     */
    function optionsSubPage()
    { ?>
        <div class="wrap">
            <h1>Word Filter Options</h1>
            <form action="options.php" method="post">
                <?php
                settings_errors();
                settings_fields('replacementFields');
                do_settings_sections('word-filter-options');
                submit_button();
                ?>
            </form>
        </div>
    <?php }

}
$ourWordFilterPlugin = new OurWordFilterPlugin();