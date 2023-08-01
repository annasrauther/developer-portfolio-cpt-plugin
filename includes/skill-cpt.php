<?php
/**
 * Register the 'Skills' Custom Post Type.
 *
 * Registers the 'Skills' custom post type, representing various skills used in the portfolio.
 * Enables REST API support and allows users to add custom meta fields for the skill details.
 */
function developer_portfolio_register_skills_cpt() {
    register_post_type('skill', array(
        'labels' => array(
            'name' => 'Skills',
            'singular_name' => 'Skill',
            'add_new' => 'Add New Skill',
            'edit_item' => 'Edit Skill',
            'new_item' => 'New Skill',
            'view_item' => 'View Skill',
            'search_items' => 'Search Skills',
            'not_found' => 'No skills found',
            'not_found_in_trash' => 'No skills found in Trash',
            'all_items' => 'All Skills',
        ),
        'menu_icon' => 'dashicons-html',
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'thumbnail'), // Added support for featured images
        'show_in_rest' => true, // To enable REST API support
    ));
}
add_action('init', 'developer_portfolio_register_skills_cpt');

/**
 * Add custom meta box for 'Skills' custom post type.
 *
 * Adds a custom meta box to the 'Skills' custom post type.
 * The meta box allows users to add additional details such as type and description for each skill entry.
 */
function developer_portfolio_add_skills_metabox($meta_boxes) {
    $meta_boxes[] = array(
        'id' => 'skills_details',
        'title' => 'Skill Details',
        'post_types' => array('skill'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => 'Type',
                'id' => 'type',
                'type' => 'text',
            ),
            array(
                'name' => 'Description',
                'id' => 'description',
                'type' => 'textarea',
            ),
            array(
                'name' => 'Featured',
                'id' => 'featured',
                'type' => 'checkbox',
                'std' => 'false',
            ),
        ),
    );
    return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'developer_portfolio_add_skills_metabox');

/**
 * Modify the REST API response to show custom fields inside the 'payload' object.
 *
 * Modifies the REST API response to include custom fields inside the 'payload' object for the 'Skills' custom post type.
 * This ensures that the custom fields (type, description, and featured) are available when fetching skill data from the REST API.
 *
 * @param WP_REST_Response $response The REST API response object.
 * @param WP_Post          $post     The 'Skills' post object.
 * @param WP_REST_Request  $request  The REST API request object.
 * @return WP_REST_Response Modified REST API response object.
 */
function developer_portfolio_modify_skills_rest_api_response($response, $post, $request) {
    $custom_fields = array(
        'type' => get_post_meta($post->ID, 'type', true),
        'description' => get_post_meta($post->ID, 'description', true),
        'featured' => (bool) get_post_meta($post->ID, 'featured', true),
    );

    $response->data['payload'] = $custom_fields;
    return $response;
}
add_filter('rest_prepare_skill', 'developer_portfolio_modify_skills_rest_api_response', 10, 3);

/**
 * Add a new column for the "featured" tag on the skills list page.
 *
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function developer_portfolio_add_skills_list_column($columns) {
    $columns['featured'] = 'Featured';
    return $columns;
}
add_filter('manage_skill_posts_columns', 'developer_portfolio_add_skills_list_column');

/**
 * Display the "featured" tag as a star icon on the skills list page.
 *
 * @param string $column Column name.
 * @param int $post_id Post ID.
 */
function developer_portfolio_display_skills_list_column($column, $post_id) {
    if ($column === 'featured') {
        $featured = get_post_meta($post_id, 'featured', true);
        echo $featured ? '<span style="color: #2372b1;" class="dashicons dashicons-star-filled"></span>' : '<span class="dashicons dashicons-star-empty"></span>';
    }
}
add_action('manage_skill_posts_custom_column', 'developer_portfolio_display_skills_list_column', 10, 2);

/**
 * Move the featured image meta box below the title in the post editor.
 */
function developer_portfolio_move_featured_image_meta_box() {
    remove_meta_box('postimagediv', 'skill', 'side');
    add_meta_box('postimagediv', __('Skill Image'), 'post_thumbnail_meta_box', 'skill', 'normal', 'high');
}
add_action('edit_form_after_title', 'developer_portfolio_move_featured_image_meta_box');
