<?php
namespace {{PLUGIN_NS}}\{{DIRECTORY_NS}};

class {{CONTROLLER_NAME}} extends \WP_Plugin_Maker\Custom_Type {
    public static $type = '{{CUSTOM_TYPE}}';
	
    /**
	 * @priority(0)
	 */
	public static function action_init() {
		$labels = [
			'name'                  => _x('{{CUSTOM_TYPE}}', 'Post Type General Name', '{{PLUGIN_DIR}}'),
			'singular_name'         => _x('{{CUSTOM_TYPE}}', 'Post Type Singular Name', '{{PLUGIN_DIR}}'),
			'menu_name'             => __('{{CUSTOM_TYPE}}', '{{PLUGIN_DIR}}'),
			'name_admin_bar'        => __('{{CUSTOM_TYPE}}', '{{PLUGIN_DIR}}'),
			'archives'              => __('Item Archives', '{{PLUGIN_DIR}}'),
			'parent_item_colon'     => __('Parent Item:', '{{PLUGIN_DIR}}'),
			'all_items'             => __('All Items', '{{PLUGIN_DIR}}'),
			'add_new_item'          => __('Add New Item', '{{PLUGIN_DIR}}'),
			'add_new'               => __('Add New', '{{PLUGIN_DIR}}'),
			'new_item'              => __('New Item', '{{PLUGIN_DIR}}'),
			'edit_item'             => __('Edit Item', '{{PLUGIN_DIR}}'),
			'update_item'           => __('Update Item', '{{PLUGIN_DIR}}'),
			'view_item'             => __('View Item', '{{PLUGIN_DIR}}'),
			'search_items'          => __('Search Item', '{{PLUGIN_DIR}}'),
			'not_found'             => __('Not found', '{{PLUGIN_DIR}}'),
			'not_found_in_trash'    => __('Not found in Trash', '{{PLUGIN_DIR}}'),
			'featured_image'        => __('Featured Image', '{{PLUGIN_DIR}}'),
			'set_featured_image'    => __('Set featured image', '{{PLUGIN_DIR}}'),
			'remove_featured_image' => __('Remove featured image', '{{PLUGIN_DIR}}'),
			'use_featured_image'    => __('Use as featured image', '{{PLUGIN_DIR}}'),
			'insert_into_item'      => __('Insert into item', '{{PLUGIN_DIR}}'),
			'uploaded_to_this_item' => __('Uploaded to this item', '{{PLUGIN_DIR}}'),
			'items_list'            => __('Items list', '{{PLUGIN_DIR}}'),
			'items_list_navigation' => __('Items list navigation', '{{PLUGIN_DIR}}'),
			'filter_items_list'     => __('Filter items list', '{{PLUGIN_DIR}}'),
        ];
		$args = [
			'label'                 => __('{{CUSTOM_TYPE}}', '{{PLUGIN_DIR}}'),
			'description'           => __('{{CUSTOM_TYPE}} Description', '{{PLUGIN_DIR}}'),
			'labels'                => $labels,
			'supports'              => ['title', 'editor'],
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 2,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => false,
			'publicly_queryable'    => false,
			'capability_type'       => '{{CUSTOM_TYPE}}',
			'menu_icon'			    => 'dashicons-post'
        ];

		register_post_type(self::$type, $args);
	}
}

