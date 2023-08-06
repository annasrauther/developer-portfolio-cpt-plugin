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
        'supports' => array('title'),
        'show_in_rest' => true,
    ));

    // Register the 'Client' custom taxonomy.
    register_taxonomy('client', 'portfolio', array(
        'label' => 'Client',
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'client'),
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
                'name' => 'Screenshot',
                'id' => 'screenshot',
                'type' => 'image_advanced',
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
                'post_type' => 'skill',
                'field_type' => 'select_advanced',
                'multiple' => true,
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
 * It adds additional data such as client, screenshot image, URL, description, and skills.
 *
 * @param WP_REST_Response $response The REST API response.
 * @param WP_Post $post The post object.
 * @param WP_REST_Request $request The REST API request.
 * @return WP_REST_Response Modified REST API response.
 */
function developer_portfolio_modify_portfolio_rest_api_response($response, $post, $request) {
    $custom_fields = array(
        'client' => '',
        'url' => get_post_meta($post->ID, 'url', true),
        'description' => get_post_meta($post->ID, 'description', true),
        'skills' => array(),
        'screenshot_url' => wp_get_attachment_url(get_post_meta($post->ID, 'screenshot', true)),
    );

    // Get the 'client' term associated with the 'portfolio' post.
    $term = get_the_terms($post->ID, 'client');
    if (!empty($term) && is_array($term)) {
        $custom_fields['client'] = $term[0]->name;
    }

    // Get the selected skill ID associated with the 'portfolio' post.
    $skill_id = get_post_meta($post->ID, 'skills', true);

    if (!empty($skill_id)) {
        // Get the skill post object using the skill ID and the 'skill' post type.
        $skill_post = get_post($skill_id, 'skill', true);

        // Add skill data to the 'skills' field in the 'payload' object.
        if ($skill_post) {
            $skill_title = $skill_post->post_title;
            $skill_image = wp_get_attachment_url(get_post_meta($skill_id, 'skill_image', true));

            $custom_fields['skills'][] = array(
                'title' => $skill_title,
                'skill_image' => $skill_image,
            );
        }
    }

    $response->data['payload'] = $custom_fields;
    return $response;
}
add_filter('rest_prepare_portfolio', 'developer_portfolio_modify_portfolio_rest_api_response', 10, 3);

/**
 * Add SCPO plugin's ordering to the list of permitted orderby values for the 'skill' post type.
 */
function filter_add_rest_orderby_params($params) {
    // Remove the default 'menu_order' from the list
    $params['orderby']['enum'] = array_diff($params['orderby']['enum'], array('menu_order'));
    // Add SCPO plugin's ordering
    $params['orderby']['enum'][] = 'scpo_order';
    return $params;
}
add_filter('rest_portfolio_collection_params', 'filter_add_rest_orderby_params', 10, 1);
