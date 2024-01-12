# Fictional University Theme 
A Classic Theme. You can purchase complete Course using this [LINK](https://www.udemy.com/become-a-wordpress-developer-php-javascript/?couponCode=LEARNWEBCODESITE)

## Required plugins for this theme:
1. [Advanced Custom Field](https://wordpress.org/plugins/advanced-custom-fields/)
1. [Members](https://wordpress.org/plugins/members/)
1. [Loco Translate](https://wordpress.org/plugins/loco-translate/) # Only for modifying language translation 

## Other Self developed Plugins

### 1. our-first-unique-plugin: 
This plugin provides post statistics. It demonstrates how to create a sub-page under the settings page/option page on the dashboard. The options page includes several form fields. Primarily, it focuses on the WordPress Settings REST API. It also provides insights into language translation in WordPress.

### 2. our-word-filter-plugin : 
This plugin filters specific words from the WordPress content on the front-end. It shows how to create a new page on the WordPress dashboard with a custom icon and sub-menu. It primarily focuses on form submission handling using a custom approach as opposed to the WordPress Settings REST API.  

### 3. are-you-paying-attention**
: This plugin creates a MCQA section on the blog content using the WordPress Gutenberg block editor. It teaches several concepts about creating WordPress Gutenberg blocks. For example: `@wordpress/components`,`useBlockProps`,`InspectorControls`, `BlockControls`, `AlignmentToolbar`,`wp.data.select("core/block-editor").getBlocks()`,`wp.data.dispatch("core/editor").lockPostSaving`,`wp.data.dispatch("core/editor").unlockPostSaving`,`wp.data.subscribe`,`registerBlockType example`,`block.json`, etc.

### 4. featured-professor : 
This plugin inserts custom post items into the content and fetches the content dynamically using the WordPress block editor. It teaches several concepts about the WordPress Gutenberg block editor. For example: registering post_meta and saving, updating, and deleting post_meta from Gutenberg blocks,`select('core').getEntityRecords('postType', 'professor', { per_page: -1 })`,`select(coreDataStore).hasFinishedResolution('getEntityRecords', ('postType', 'professor', { per_page: -1 }))`,`@wordpress/api-fetch`, internationalization at the block editor level, etc.

### 5. pets-custom-post-type : 
This basic plugin shows how to render large data using custom post types and how slow it is when it comes to search/filter.

### 6. new-database-table : 
This plugin is a custom database table version of the `pets-custom-post-type` plugin. It teaches how to get benefits from custom database tables in certain situations regarding performance. Particularly, it shows how we can boost performance over custom post types using custom database tables. It also teaches about the ==dbDelta== function in WordPress.

### 7. new-database-table-block :
This is just a block editor version of the `new-database-table` plugin with exactly the same concept.

This repository also contains the `ai1wm-backups` directory under `wp-content`, which contains database backups of this project taken using the `All-In-One WP Migration` plugin. The `All-In-One WP Migration` plugin is also included with it.

