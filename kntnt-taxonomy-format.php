<?php


/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Format Taxonomy
 * Plugin URI:        https://www.kntnt.com/
 * Description:       Provides the `format` taxonomy whose terms denote the post format (e.g. article, podcast, video or event).
 * Version:           1.0.2
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */


namespace Kntnt\Format;


defined( 'ABSPATH' ) && new Taxonomy;


class Taxonomy {

	public function __construct() {
		add_action( 'init', [ $this, 'run' ] );
	}

	public function run() {

		$slug = apply_filters( 'kntnt-taxonomy-format-slug', 'format' );
		$post_types = apply_filters( 'kntnt-taxonomy-format-objects', [ 'post' ] );

		register_taxonomy( $slug, null, $this->taxonomy( $slug ) );

		foreach ( $post_types as $post_type ) {
			register_taxonomy_for_object_type( $slug, $post_type );
		}

		add_filter( 'term_updated_messages', [ $this, 'term_updated_messages' ] );

	}

	private function taxonomy() {
		return [

			// A short descriptive summary of what the taxonomy is for.
			'description' => _x( 'Formats is a taxonomy used as post metadata. Its terms denote the content format (e.g. article, podcast, video or event).', 'Description', 'kntnt-taxonomy-format' ),

			// Whether the taxonomy is hierarchical.
			'hierarchical' => false,

			// Whether a taxonomy is intended for use publicly either via
			// the admin interface or by front-end users.
			'public' => true,

			// Whether the taxonomy is publicly queryable.
			'publicly_queryable' => true,

			// Whether to generate and allow a UI for managing terms in this
			// taxonomy in the admin.
			'show_ui' => true,

			// Whether to show the taxonomy in the admin menu.
			'show_in_menu' => true,

			// Makes this taxonomy available for selection in navigation menus.
			'show_in_nav_menus' => true,

			// Whether to list the taxonomy in the Tag Cloud Widget controls.
			'show_tagcloud' => false,

			// Whether to show the taxonomy in the quick/bulk edit panel.
			'show_in_quick_edit' => true,

			// Whether to display a column for the taxonomy on its post
			// type listing screens.
			'show_admin_column' => true,

			// Metabox to show on edit. If a callable, it is called to render
			// the metabox. If `null` the default metabox is used. If `false`,
			// no metabox is shown.
			'meta_box_cb' => false,

			// Array of capabilities for this taxonomy.
			'capabilities' => [
				'manage_terms' => 'edit_posts',
				'edit_terms' => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			],

			// Sets the query var key for this taxonomy. Default $taxonomy key.
			// If false, a taxonomy cannot be loaded
			// at ?{query_var}={term_slug}. If a string,
			// the query ?{query_var}={term_slug} will be valid.
			'query_var' => true,

			// Triggers the handling of rewrites for this taxonomy.
			// Replace the array with false to prevent handling of rewrites.
			'rewrite' => [

				// Customize the permastruct slug.
				'slug' => 'format',

				// Whether the permastruct should be prepended
				// with WP_Rewrite::$front.
				'with_front' => true,

				// Either hierarchical rewrite tag or not.
				'hierarchical' => false,

				// Endpoint mask to assign. If null and permalink_epmask
				// is set inherits from $permalink_epmask. If null and
				// permalink_epmask is not set, defaults to EP_PERMALINK.
				'ep_mask' => null,

			],

			// Default term to be used for the taxonomy.
			'default_term' => null,

			// An array of labels for this taxonomy.
			'labels' => [
				'name' => _x( 'Formats', 'Plural name', 'kntnt-taxonomy-format' ),
				'singular_name' => _x( 'Format', 'Singular name', 'kntnt-taxonomy-format' ),
				'search_items' => _x( 'Search formats', 'Search items', 'kntnt-taxonomy-format' ),
				'popular_items' => _x( 'Search formats', 'Search items', 'kntnt-taxonomy-format' ),
				'all_items' => _x( 'All formats', 'All items', 'kntnt-taxonomy-format' ),
				'parent_item' => _x( 'Parent format', 'Parent item', 'kntnt-taxonomy-format' ),
				'parent_item_colon' => _x( 'Parent format colon', 'Parent item colon', 'kntnt-taxonomy-format' ),
				'edit_item' => _x( 'Edit format', 'Edit item', 'kntnt-taxonomy-format' ),
				'view_item' => _x( 'View format', 'View item', 'kntnt-taxonomy-format' ),
				'update_item' => _x( 'Update format', 'Update item', 'kntnt-taxonomy-format' ),
				'add_new_item' => _x( 'Add new format', 'Add new item', 'kntnt-taxonomy-format' ),
				'new_item_name' => _x( 'New format name', 'New item name', 'kntnt-taxonomy-format' ),
				'separate_items_with_commas' => _x( 'Separate formats with commas', 'Separate items with commas', 'kntnt-taxonomy-format' ),
				'add_or_remove_items' => _x( 'Add or remove formats', 'Add or remove items', 'kntnt-taxonomy-format' ),
				'choose_from_most_used' => _x( 'Choose from most used', 'Choose from most used', 'kntnt-taxonomy-format' ),
				'not_found' => _x( 'Not found', 'Not found', 'kntnt-taxonomy-format' ),
				'no_terms' => _x( 'No terms', 'No terms', 'kntnt-taxonomy-format' ),
				'items_list_navigation' => _x( 'Formats list navigation', 'Items list navigation', 'kntnt-taxonomy-format' ),
				'items_list' => _x( 'Items list', 'Formats list', 'kntnt-taxonomy-format' ),
				'most_used' => _x( 'Most used', 'Most used', 'kntnt-taxonomy-format' ),
				'back_to_items' => _x( 'Back to formats', 'Back to items', 'kntnt-taxonomy-format' ),
			],

		];
	}

	public function term_updated_messages( $messages ) {
		$messages['format'] = [
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Format added.', 'kntnt-taxonomy-format' ),
			2 => __( 'Format deleted.', 'kntnt-taxonomy-format' ),
			3 => __( 'Format updated.', 'kntnt-taxonomy-format' ),
			4 => __( 'Format not added.', 'kntnt-taxonomy-format' ),
			5 => __( 'Format not updated.', 'kntnt-taxonomy-format' ),
			6 => __( 'Formats deleted.', 'kntnt-taxonomy-format' ),
		];
		return $messages;
	}

}