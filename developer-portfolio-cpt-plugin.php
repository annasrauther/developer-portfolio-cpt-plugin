<?php
/**
 * Plugin Name: Developer Portfolio CPT
 * Description: A custom post type plugin for managing the Developer Portfolio content. This plugin registers several Custom Post Types (CPTs) to showcase skills, experiences, and portfolios on the website.
 * Author: Al Annas Rauther
 * Author URI: https://annasrauther.com
 * Version: 1.0
 * License: GPL2
 *
 * @package Developer_Portfolio_CPT
 */

// Prevent direct access to this file.
if (!defined('ABSPATH')) {
    exit;
}

// Define the plugin version.
define('DEVELOPER_PORTFOLIO_CPT_VERSION', '1.0');

// Plugin directory path.
define('DEVELOPER_PORTFOLIO_CPT_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Plugin directory URL.
define('DEVELOPER_PORTFOLIO_CPT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include individual Custom Post Type (CPT) files.

/**
 * Include the 'Skill' Custom Post Type file.
 */
require_once DEVELOPER_PORTFOLIO_CPT_PLUGIN_DIR . 'includes/skill-cpt.php';

/**
 * Include the 'Experience' Custom Post Type file.
 */
require_once DEVELOPER_PORTFOLIO_CPT_PLUGIN_DIR . 'includes/experience-cpt.php';

/**
 * Include the 'Portfolio' Custom Post Type file.
 */
require_once DEVELOPER_PORTFOLIO_CPT_PLUGIN_DIR . 'includes/portfolio-cpt.php';

/**
 * Include the 'About' Options file.
 */
require_once DEVELOPER_PORTFOLIO_CPT_PLUGIN_DIR . 'includes/about-options.php';