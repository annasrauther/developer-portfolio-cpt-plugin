<?php
/**
 * Register the 'Portfolio' Custom Post Type.
 *
 * Registers the custom post type 'portfolio' to showcase portfolios on the website.
 * The custom post type supports the 'title' field and is set to be public and have archives.
 * Additionally, it is enabled to be shown in the REST API for external consumption.
 */
function developer_portfolio_register_portfolio_cpt() {
    register_post_type('portfolio', array(
        'labels' => array(
            'name' => 'Portfolios',
            'singular_name' => 'Portfolio',
            'add_new' => 'Add New Portfolio',
            'edit_item' => 'Edit Portfolio',
            'new_item' => 'New Portfolio',
            'view_item' => 'View Portfolio',
            'search_items' => 'Search Portfolios',
            'not_found' => 'No Portfolios found',
            'not_found_in_trash' => 'No Portfolios found in Trash',
            'all_items' => 'All Portfolios',
        ),
        'menu_icon' => 'dashicons-format-gallery', 
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'thumbnail'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'developer_portfolio_register_portfolio_cpt');

/**
 * Add custom meta box for 'portfolio' custom post type.
 *
 * Adds a custom meta box named 'Portfolio Details' to the 'portfolio' custom post type.
 * The meta box allows users to enter additional details for each portfolio entry, including client, URL, description, and skills.
 */
function developer_portfolio_add_portfolio_metabox($meta_boxes) {
    $meta_boxes[] = array(
        'id' => 'portfolio_details',
        'title' => 'Portfolio Details',
        'post_types' => array('portfolio'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => 'Client',
                'id' => 'client',
                'type' => 'text',
            ),
            array(
                'name' => 'URL',
                'id' => 'url',
                'type' => 'url',
            ),
            array(
                'name' => 'Description',
                'id' => 'description',
                'type' => 'wysiwyg',
            ),
            array(
                'name' => 'Skills',
                'id' => 'skills',
                'type' => 'post',
                'post_type' => 'skills',
                'field_type' => 'select_advanced',
                'multiple' => true,
                'clone' => true,
            ),
        ),
    );
    return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'developer_portfolio_add_portfolio_metabox');

/**
 * Modify the REST API response to show custom fields inside the 'payload' object.
 *
 * Modifies the REST API response for the 'portfolio' custom post type to include custom fields inside the 'payload' object.
 * It adds additional data such as client, featured image URL, URL, description, and skills.
 *
 * @param WP_REST_Response $response The REST API response.
 * @param WP_Post $post The post object.
 * @param WP_REST_Request $request The REST API request.
 * @return WP_REST_Response Modified REST API response.
 */
function developer_portfolio_modify_portfolio_rest_api_response($response, $post, $request) {
    $response->data['payload'] = array(
        'client' => get_post_meta($post->ID, 'client', true),
        'featured_image' => get_the_post_thumbnail_url($post->ID, 'full'),
        'url' => get_post_meta($post->ID, 'url', true),
        'description' => get_post_meta($post->ID, 'description', true),
        'skills' => get_post_meta($post->ID, 'skills', true),
    );
    return $response;
}
add_filter('rest_prepare_portfolio', 'developer_portfolio_modify_portfolio_rest_api_response', 10, 3);
