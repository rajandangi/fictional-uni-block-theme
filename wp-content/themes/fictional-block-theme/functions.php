<?php

require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');


/**
 * Registers custom REST fields for the 'post' and 'note' endpoints.
 *
 * This function is hooked into the 'rest_api_init' action and adds two custom fields:
 * - 'authorName' for the 'post' endpoint, which returns the name of the post author.
 * - 'userNoteCount' for the 'note' endpoint, which returns the number of notes created by the current user.
 *
 * @since 1.0.0
 */
add_action('rest_api_init', 'university_custom_rest');
function university_custom_rest()
{
    register_rest_field(
        'post',
        'authorName',
        array(
            'get_callback' => function () {
                return get_the_author();
            }
        )
    );
    register_rest_field(
        'note',
        'userNoteCount',
        array(
            'get_callback' => function () {
                return count_user_posts(get_current_user_id(), 'note');
            }
        )
    );
}


function pageBanner($args = null)
{
    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
    }
    if (!isset($args['subtitle'])) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if (!isset($args['photo'])) {
        if (get_field('page_banner_background_image') and !is_archive() and !is_home()) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">
                <?php echo $args['title']; ?>
            </h1>
            <div class="page-banner__intro">
                <p>
                    <?php echo $args['subtitle']; ?>
                </p>
            </div>
        </div>
    </div>
<?php
}

function university_files()
{
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyD99o_yXLkIIlSPBCF5VtN53_-iJELxY6Q', NULL, '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_style(
        'custom_google_fonts',
        'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i'
    );
    wp_enqueue_style('font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('build/index.css'));

    wp_localize_script(
        'main-university-js',
        'universityData',
        array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest')
        )
    );
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features()
{
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);

    // Also Add CSS to Gutenberg Editor
    add_theme_support('editor-styles');
    add_editor_style(array('https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i', 'build/style-index.css', 'build/index.css'));
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query)
{
    if (!is_admin() and is_post_type_archive('campus') and $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }
    if (!is_admin() and is_post_type_archive('program') and $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
    if (!is_admin() and is_post_type_archive('event') and $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set(
            'meta_query',
            array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                )
            )
        );
    }
}

add_action('pre_get_posts', 'university_adjust_queries');
function universityMapKey($api)
{
    $api['key'] = 'AIzaSyD99o_yXLkIIlSPBCF5VtN53_-iJELxY6Q';
    return $api;
}

add_action('acf/fields/google_map/api', 'universityMapKey');

// Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontend');
function redirectSubsToFrontend()
{
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) === 1 && $ourCurrentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}
// Remove admin bar for subscribers
add_action('wp_loaded', 'noSubsAdminBar');
function noSubsAdminBar()
{
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) === 1 && $ourCurrentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}

// Customize Login Screen

/**
 * Sets the login header URL.
 *
 * This function is used as a filter for the 'login_headerurl' hook. It returns the URL of the site's homepage.
 *
 * @return string The URL of the site's homepage.
 */
add_filter('login_headerurl', 'ourHeaderUrl');
function ourHeaderUrl()
{
    return esc_url(site_url('/'));
}

/**
 * Enqueues custom CSS styles for the login page.
 *
 * This function is hooked to the 'login_enqueue_scripts' action and is responsible for
 * enqueueing the necessary CSS styles for the login page. It adds styles for custom Google fonts,
 * Font Awesome icons, and the main and extra stylesheets of the theme.
 *
 * @since 1.0.0
 */
add_action('login_enqueue_scripts', 'ourLoginCSS');
function ourLoginCSS()
{
    wp_enqueue_style(
        'custom_google_fonts',
        'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i'
    );
    wp_enqueue_style('font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('build/index.css'));
}

/**
 * Sets the login header title to the site name.
 *
 * @return string The site name.
 */
add_filter('login_headertext', 'ourLoginTitle');
function ourLoginTitle()
{
    return get_bloginfo('name');
}

// Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);
function makeNotePrivate($data, $postarr)
{
    if ($data['post_type'] == 'note') {
        // Check if user has more than 4 notes and deny publishing if true and post is new (not an update or delete)
        if (count_user_posts(get_current_user_id(), 'note') > 4 && !$postarr['ID']) {
            die("You have reached your note limit.");
        }
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    if ($data['post_type'] == 'note' && $data['post_status'] != 'trash') {
        $data['post_status'] = 'private';
    }
    return $data;
}


class PlaceholderBlock
{
    private $name;

    function __construct($name)
    {
        $this->name = $name;
        add_action('init', [$this, 'onInit']);
    }

    function ourRenderCallback($attributes, $content)
    {
        ob_start();
        require get_theme_file_path("/our-blocks/{$this->name}.php");
        return ob_get_clean();
    }

    function onInit()
    {
        wp_register_script(
            $this->name,
            get_stylesheet_directory_uri() . "/our-blocks/{$this->name}.js",
            array('wp-blocks', 'wp-editor')
        );

        register_block_type(
            "ourblocktheme/{$this->name}",
            array(
                'editor_script' => $this->name,
                'render_callback' => [$this, 'ourRenderCallback']
            )
        );
    }
}

// Register New blocks
function fictional_university_blocks()
{
    register_block_type_from_metadata(__DIR__ . '/build/footer');
    register_block_type_from_metadata(__DIR__ . '/build/header');
    register_block_type_from_metadata(__DIR__ . '/build/eventsandblogs');
    register_block_type_from_metadata(__DIR__ . '/build/archive');
    register_block_type_from_metadata(__DIR__ . '/build/archivecampus');
    register_block_type_from_metadata(__DIR__ . '/build/singlepost');
    register_block_type_from_metadata(__DIR__ . '/build/page');
    register_block_type_from_metadata(__DIR__ . '/build/blogindex');
    register_block_type_from_metadata(__DIR__ . '/build/programarchive');
    register_block_type_from_metadata(__DIR__ . '/build/singleprogram');
    register_block_type_from_metadata(__DIR__ . '/build/singleprofessor');
    register_block_type_from_metadata(__DIR__ . '/build/mynotes');
    register_block_type_from_metadata(__DIR__ . '/build/archiveevent');
    register_block_type_from_metadata(__DIR__ . '/build/pastevents');
    register_block_type_from_metadata(__DIR__ . '/build/search');
    register_block_type_from_metadata(__DIR__ . '/build/searchresults');
    register_block_type_from_metadata(__DIR__ . '/build/singlecampus');
    register_block_type_from_metadata(__DIR__ . '/build/singleevent');
}
add_action('init', 'fictional_university_blocks');


class JSXBlock
{
    private $name;
    private $renderCallback;
    private $data;

    function __construct($name, $renderCallback = null, $data = null)
    {
        $this->name = $name;
        $this->data = $data;
        $this->renderCallback = $renderCallback;
        add_action('init', [$this, 'onInit']);
    }

    function ourRenderCallback($attributes, $content)
    {
        ob_start();
        require get_theme_file_path("/our-blocks/{$this->name}.php");
        return ob_get_clean();
    }

    function onInit()
    {
        wp_register_script(
            $this->name,
            get_stylesheet_directory_uri() . "/build/{$this->name}.js",
            array('wp-blocks', 'wp-editor')
        );

        if ($this->data) {
            wp_localize_script($this->name, $this->name, $this->data);
        }

        $ourArgs = array(
            'editor_script' => $this->name,
        );
        if ($this->renderCallback) {
            $ourArgs['render_callback'] = [$this, 'ourRenderCallback'];
        }

        register_block_type("ourblocktheme/{$this->name}", $ourArgs);
    }
}
new JSXBlock('banner', true);
new JSXBlock('genericheading');
new JSXBlock('genericbutton');
new JSXBlock('slideshow', true);
new JSXBlock('slide', true, ['themeimagepath' => get_theme_file_uri('/images/')]);


function myallowedblocks($allowed_block_types, $editor_context)
{
    // if you are on a page/post editor screen
    if (!empty($editor_context->post)) {
        return $allowed_block_types;
    }
    // if you are on the FSE screen
    return array('ourblocktheme/header', 'ourblocktheme/footer');
}
add_filter('allowed_block_types_all', 'myallowedblocks', 10, 2);
