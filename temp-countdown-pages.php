<?php
/*
Plugin Name: WP Temp Countdown Pages
Plugin URI: 
Description: A plugin to create, edit, and manage temporary countdown pages with custom URLs and admin control.
Version: 1.0
Author: Hamdy Hussein
Author URI: 
License: GPL2
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Add admin menu
add_action('admin_menu', 'wp_temp_countdown_page_menu');

function wp_temp_countdown_page_menu() {
    add_menu_page(
        'Countdown Pages',
        'Countdown Pages',
        'manage_options',
        'wp-temp-countdown-pages',
        'wp_temp_countdown_page_options',
        'dashicons-calendar-alt',
        20
    );

    add_submenu_page(
        'wp-temp-countdown-pages',
        'Add New Countdown Page',
        'Add New',
        'manage_options',
        'wp-temp-countdown-pages',
        'wp_temp_countdown_page_options'
    );

    add_submenu_page(
        'wp-temp-countdown-pages',
        'All Countdown Pages',
        'All Pages',
        'manage_options',
        'all-countdown-pages',
        'all_countdown_pages'
    );
}

function wp_temp_countdown_page_options() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    if (isset($_POST['save_temp_page'])) {
        $page_title = sanitize_text_field($_POST['page_title']);
        $page_url = sanitize_text_field($_POST['page_url']);
        $end_datetime = sanitize_text_field($_POST['end_datetime']);
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;

        if (empty($page_title) || empty($page_url) || empty($end_datetime)) {
            echo '<div class="error"><p>All fields are required to create a countdown page.</p></div>';
        } else {
            $page_data = array(
                'post_title'    => $page_title,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => get_current_user_id(),
                'post_name'     => $page_url,
                'ID'            => $page_id,
                'post_type'     => 'page',
            );
            $page_id = wp_insert_post($page_data);

            if ($page_id) {
                update_post_meta($page_id, 'end_datetime', $end_datetime);
                echo '<div class="updated"><p>Page updated successfully!</p></div>';
            }
        }
    }

    $page_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
    $page = $page_id ? get_post($page_id) : null;
    $end_datetime = $page ? get_post_meta($page->ID, 'end_datetime', true) : '';
    ?>
    <div class="wrap">
        <h1><?php echo $page ? 'Edit Countdown Page' : 'Create Temporary Countdown Page'; ?></h1>
        <form method="post" action="">
            <input type="hidden" name="page_id" value="<?php echo esc_attr($page ? $page->ID : ''); ?>">
            <table class="form-table">
                <tr>
                    <th><label for="page_title">Page Title</label></th>
                    <td><input type="text" id="page_title" name="page_title" class="regular-text" value="<?php echo esc_attr($page ? $page->post_title : ''); ?>" required></td>
                </tr>
                <tr>
                    <th><label for="page_url">Page URL</label></th>
                    <td><input type="text" id="page_url" name="page_url" class="regular-text" value="<?php echo esc_attr($page ? $page->post_name : ''); ?>" required></td>
                </tr>
                <tr>
                    <th><label for="end_datetime">End Date and Time</label></th>
                    <td><input type="text" id="end_datetime" name="end_datetime" class="regular-text datetime-picker" value="<?php echo esc_attr($end_datetime); ?>" required></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="save_temp_page" id="save_temp_page" class="button button-primary" value="<?php echo $page ? 'Update Page' : 'Create Page'; ?>">
            </p>
        </form>
    </div>
    <?php
}

function all_countdown_pages() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $args = array(
        'post_type'  => 'page',
        'meta_key'   => 'end_datetime',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );
    $pages = get_posts($args);
    ?>
    <div class="wrap">
        <h1>All Countdown Pages</h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">URL</th>
                    <th scope="col">End Date and Time</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page) {
                    $end_datetime = get_post_meta($page->ID, 'end_datetime', true);
                    $current_time = current_time('Y-m-d H:i:s');
                    $is_expired = strtotime($end_datetime) < strtotime($current_time);
                    ?>
                    <tr>
                        <td><?php echo esc_html($page->post_title); ?></td>
                        <td><a href="<?php echo esc_url(get_permalink($page->ID)); ?>" target="_blank"><?php echo esc_url(get_permalink($page->ID)); ?></a></td>
                        <td style="color: <?php echo $is_expired ? 'red' : 'black'; ?>;"><?php echo esc_html($end_datetime); ?></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=wp-temp-countdown-pages&edit=' . $page->ID); ?>" class="button">Edit</a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=delete_temp_page&id=' . $page->ID), 'delete_temp_page_' . $page->ID); ?>" class="button button-danger">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Enqueue Flatpickr styles and scripts
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');

function enqueue_admin_scripts() {
    wp_enqueue_style('flatpickr-style', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
    wp_enqueue_script('flatpickr-script', 'https://cdn.jsdelivr.net/npm/flatpickr', array(), null, true);
    wp_add_inline_script('flatpickr-script', 'document.addEventListener("DOMContentLoaded", function() { flatpickr(".datetime-picker", { enableTime: true, dateFormat: "Y-m-d H:i:S", allowInput: true }); });');
}

// Handle page deletion
add_action('admin_post_delete_temp_page', 'handle_delete_temp_page');

function handle_delete_temp_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    if (isset($_GET['id']) && wp_verify_nonce($_GET['_wpnonce'], 'delete_temp_page_' . $_GET['id'])) {
        $page_id = intval($_GET['id']);
        wp_delete_post($page_id, true);
        wp_redirect(admin_url('admin.php?page=all-countdown-pages'));
        exit;
    }
}

// Redirect expired pages
add_action('template_redirect', 'redirect_expired_pages');

function redirect_expired_pages() {
    if (is_page()) {
        global $post;
        $end_datetime = get_post_meta($post->ID, 'end_datetime', true);
        if ($end_datetime) {
            $current_time = current_time('Y-m-d H:i:s');
            if (strtotime($end_datetime) < strtotime($current_time)) {
                wp_redirect(home_url());
                exit;
            }
        }
    }
}

// Save countdown end datetime on page update
add_action('save_post', 'save_countdown_end_datetime');

function save_countdown_end_datetime($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['end_datetime'])) {
        update_post_meta($post_id, 'end_datetime', sanitize_text_field($_POST['end_datetime']));
    }
}
