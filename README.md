# Fictional University Block Theme 

A Classic Theme. You can purchase the complete Course using this [LINK](https://www.udemy.com/become-a-wordpress-developer-php-javascript/?couponCode=LEARNWEBCODESITE)

## Required Plugins
- [Advanced Custom Field](https://wordpress.org/plugins/advanced-custom-fields/)
- [Members](https://wordpress.org/plugins/members/)
- [Loco Translate](https://wordpress.org/plugins/loco-translate/) (Only for modifying language translation)

## Custom Plugins

### 1. our-first-unique-plugin
A plugin that provides post statistics. Features include:
- Creates a sub-page under the settings page/dashboard
- Implements various form fields
- Focuses on WordPress Settings REST API
- Includes language translation functionality

### 2. our-word-filter-plugin
A content filtering plugin that:
- Filters specific words from WordPress front-end content
- Creates custom dashboard page with unique icon and sub-menu
- Implements custom form submission handling
- Alternative to WordPress Settings REST API

### 3. are-you-paying-attention
A MCQA block plugin demonstrating various Gutenberg concepts:
- `@wordpress/components`
- `useBlockProps`
- `InspectorControls`
- `BlockControls`
- `AlignmentToolbar`
- WordPress data operations:
  - `wp.data.select("core/block-editor").getBlocks()`
  - `wp.data.dispatch("core/editor").lockPostSaving`
  - `wp.data.dispatch("core/editor").unlockPostSaving`
  - `wp.data.subscribe`
- Block registration and configuration

### 4. featured-professor
A dynamic content block plugin showcasing:
- Custom post integration
- Post meta handling in Gutenberg
- Advanced data selection:
  ```js
  select('core').getEntityRecords('postType', 'professor', { per_page: -1 })
  select(coreDataStore).hasFinishedResolution(...)
  ```
- `@wordpress/api-fetch` implementation
- Block-level internationalization

### 5. new-database-table-block
A block editor implementation of the `new-database-table` plugin.

---

## Additional Resources
The repository includes the `ai1wm-backups` directory under `wp-content`, containing database backups created with the `All-In-One WP Migration` plugin (included).