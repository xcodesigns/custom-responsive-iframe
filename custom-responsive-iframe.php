<?php
/**
 * Plugin Name: Custom Responsive Iframe
 * Description: A plugin to embed a custom iframe with a source URL defined in the admin settings.
 * Version: 1.0
 * Author: XcoDesigns
 */

// Admin menu for setting iframe source URL
function custom_iframe_menu() {
    add_menu_page('Custom Iframe Settings', 'Custom Iframe', 'manage_options', 'custom_iframe_settings', 'custom_iframe_settings_page', '', 200);
}

function custom_iframe_settings_page() {
    ?>
    <div class="wrap">
        <h2>Custom Iframe Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('custom_iframe_settings');
            do_settings_sections('custom_iframe_settings');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Iframe Source URL</th>
                    <td><input type="text" name="iframe_source_url" value="<?php echo esc_attr(get_option('iframe_source_url')); ?>" style="width:400px;"/></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function custom_iframe_settings() {
    register_setting('custom_iframe_settings', 'iframe_source_url');
}

add_action('admin_menu', 'custom_iframe_menu');
add_action('admin_init', 'custom_iframe_settings');

// AJAX function for proxying iframe content
function custom_responsive_iframe_proxy() {
    $iframe_url = get_option('iframe_source_url');

    $response = wp_remote_get($iframe_url);

    if (is_wp_error($response)) {
        echo "Failed to fetch content.";
        wp_die();
    }

    echo wp_remote_retrieve_body($response);
    wp_die();
}

add_action('wp_ajax_nopriv_fetch_iframe_content', 'custom_responsive_iframe_proxy');
add_action('wp_ajax_fetch_iframe_content', 'custom_responsive_iframe_proxy');

// Enqueue script for handling AJAX request
function custom_responsive_iframe_enqueue_scripts() {
    wp_enqueue_script('custom_responsive_iframe', plugins_url('/custom-responsive-iframe.js', __FILE__), array('jquery'), null, true);

    $iframe_url = get_option('iframe_source_url');
    wp_localize_script('custom_responsive_iframe', 'custom_iframe_vars', array('iframe_url' => $iframe_url));
}

add_action('wp_enqueue_scripts', 'custom_responsive_iframe_enqueue_scripts');

// Shortcode function
function custom_iframe_shortcode() {
    return '<div id="custom-iframe-container"></div>';
}
add_shortcode('custom_iframe', 'custom_iframe_shortcode');
?>
