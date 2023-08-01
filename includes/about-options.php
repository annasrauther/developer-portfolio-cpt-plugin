<?php
/**
 * Add custom options page for Developer Bio content.
 *
 * Registers a custom options page in the WordPress admin to store the bio content for the Developer Bio.
 */
function developer_portfolio_add_about_options_page() {
    add_menu_page(
        'Developer Bio',                            // Page Title displayed in the browser tab
        'Developer Bio',                            // Menu Title displayed in the WordPress admin sidebar
        'manage_options',                           // Required capability to access the options page
        'developer-portfolio-about-options',        // Unique slug used to identify the options page
        'developer_portfolio_about_options_callback', // Callback function to render the options page content
        'dashicons-admin-users',                    // Menu icon (Replace with your preferred dashicon)
        25                                         // Menu position (Change as needed to place it correctly)
    );
}
add_action('admin_menu', 'developer_portfolio_add_about_options_page');

/**
 * Register Meta Boxes for the Developer Bio Content.
 */
function developer_portfolio_about_meta_boxes($meta_boxes) {
    $meta_boxes[] = array(
        'id' => 'developer_bio_content',
        'title' => 'Bio Content',
        'post_types' => 'page', // Change this to your About page post type if it's different
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => 'Bio Content',
                'id' => 'bio_content',
                'type' => 'wysiwyg',
            ),
        ),
        'show_in_rest' => true,
    );
    return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'developer_portfolio_about_meta_boxes');

/**
 * Callback function for the Developer Bio Content options page.
 *
 * This function displays the options page to manage the bio content for the Developer Bio.
 * The bio content is stored using the WordPress options API.
 */
function developer_portfolio_about_options_callback() {
    ?>
    <div class="wrap">
        <h1>Developer Bio</h1>
        <p>Please add your bio for the About us content</p>
        <?php
        // Get the saved bio content from options
        $bio_content = get_option('developer_portfolio_about_bio');

        // Handle form submission
        if (isset($_POST['submit'])) {
            check_admin_referer('developer_portfolio_about_options'); // Security check for the form submission
            $bio_content = $_POST['bio_content'];
            update_option('developer_portfolio_about_bio', $bio_content); // Save the bio content to WordPress options
            ?>
            <div class="notice notice-success is-dismissible">
                <p>Bio content updated successfully!</p>
            </div>
            <?php
        }
        ?>
        <form method="post">
            <?php wp_nonce_field('developer_portfolio_about_options'); ?>
            <div>
                <h2>Bio Content:</h2>
                <?php
                // Output the bio content field using WordPress editor
                wp_editor(
                    $bio_content,
                    'bio_content',
                    array(
                        'textarea_rows' => 10,
                        'media_buttons' => false, // Remove media buttons from the editor
                    )
                );
                ?>
            </div>
            <br>
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="Save Bio Content">
            </p>
        </form>
    </div>
    <?php
}

/**
 * Save the bio content when the options page form is submitted.
 */
function developer_portfolio_save_about_options() {
    if (isset($_POST['submit'])) {
        if (isset($_POST['bio_content'])) {
            $bio_content = wp_kses_post($_POST['bio_content']);
            update_option('developer_portfolio_about_bio', $bio_content); // Save the bio content to WordPress options
        }
    }
}
add_action('admin_post_developer_portfolio_about_options', 'developer_portfolio_save_about_options');

/**
 * Register a custom REST API route to fetch the bio content.
 */
function developer_portfolio_register_rest_route() {
    register_rest_route('wp/v2', '/about', array(
        'methods' => 'GET',
        'callback' => 'developer_portfolio_get_about_content',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'developer_portfolio_register_rest_route');

/**
 * Callback function to get the bio content from WordPress options.
 */
function developer_portfolio_get_about_content() {
    $bio_content = get_option('developer_portfolio_about_bio', '');
    return array(
        'bio_content' => $bio_content,
    );
}
