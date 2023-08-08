<?php
/**
 * Register the 'Experience' Custom Post Type.
 *
 * Registers the 'Experience' custom post type to showcase work experiences on the website.
 * The custom post type supports the 'title' field and is set to be public and have archives.
 * Additionally, it is enabled to be shown in the REST API for external consumption.
 */
function developer_portfolio_register_experience_cpt() {
    register_post_type('experience', array(
        'labels' => array(
            'name' => 'Experiences',
            'singular_name' => 'Experience',
            'add_new' => 'Add New Experience',
            'edit_item' => 'Edit Experience',
            'new_item' => 'New Experience',
            'view_item' => 'View Experience',
            'search_items' => 'Search Experiences',
            'not_found' => 'No experiences found',
            'not_found_in_trash' => 'No experiences found in Trash',
            'all_items' => 'All Experiences',
        ),
        'menu_icon' => 'dashicons-chart-bar',
        'public' => true,
        'hierarchical' => true,
        'has_archive' => true,
        'supports' => array('title', 'page-attributes'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'developer_portfolio_register_experience_cpt');

/**
 * Add custom meta box for 'Experience' custom post type.
 *
 * Adds a custom meta box named 'Experience Details' to the 'experience' custom post type.
 * The meta box allows users to enter additional details for each work experience, including
 * company name, company location, company logo, company URL, duration, and description.
 */
function developer_portfolio_add_experience_meta_boxes($meta_boxes) {
    $meta_boxes[] = array(
        'id' => 'experience_details',
        'title' => 'Experience Details',
        'post_types' => array('experience'),
        'context' => 'normal',
        'priority' => 'high',
        'show_in_rest' => true,
        'fields' => array(
            array(
                'name' => 'Company Name',
                'id' => 'company_name',
                'type' => 'text',
            ),
            array(
                'name' => 'Company Location',
                'id' => 'company_location',
                'type' => 'text',
            ),
            array(
                'name' => 'Company Logo',
                'id' => 'company_logo',
                'type' => 'image_advanced',
            ),
            array(
                'name' => 'Company URL',
                'id' => 'company_url',
                'type' => 'url',
            ),
            array(
                'name' => 'Duration',
                'id' => 'duration',
                'type' => 'text',
            ),
            array(
                'name' => 'Description',
                'id' => 'description',
                'type' => 'wysiwyg',
            ),
        ),
    );

    return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'developer_portfolio_add_experience_meta_boxes');

/**
 * Modify the REST API response to show custom fields inside the 'payload' object
 * for 'Experience' custom post type.
 *
 * This function modifies the REST API response for the 'experience' custom post type
 * to include custom fields inside the 'payload' object. It adds additional data such as
 * company name, company location, company logo URL, company URL, duration, and description.
 *
 * @param WP_REST_Response $response The REST API response.
 * @param WP_Post $post The post object.
 * @param WP_REST_Request $request The REST API request.
 * @return WP_REST_Response Modified REST API response.
 */
function developer_portfolio_modify_experience_rest_api_response($response, $post, $request) {
    $company_logo_id = get_post_meta($post->ID, 'company_logo', true);
    $company_logo_url = wp_get_attachment_url($company_logo_id);

    $response->data['payload'] = array(
        'company_name' => get_post_meta($post->ID, 'company_name', true),
        'company_location' => get_post_meta($post->ID, 'company_location', true),
        'company_logo' => $company_logo_url,
        'company_url' => get_post_meta($post->ID, 'company_url', true),
        'duration' => get_post_meta($post->ID, 'duration', true),
        'description' => get_post_meta($post->ID, 'description', true),
    );
    return $response;
}
add_filter('rest_prepare_experience', 'developer_portfolio_modify_experience_rest_api_response', 10, 3);

/**
 * Add SCPO plugin's ordering to the list of permitted orderby values for the 'skill' post type.
 */
function developer_experience_filter_add_rest_experience_orderby_params($params) {
    // Add SCPO plugin's ordering to the list of permitted orderby values.
    $params['orderby']['enum'][] = 'menu_order';
    $params['per_page']['default'] = 100;
    return $params;
}
add_filter('rest_experience_collection_params', 'developer_experience_filter_add_rest_experience_orderby_params', 10, 1);
