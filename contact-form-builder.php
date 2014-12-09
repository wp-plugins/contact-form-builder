<?php
/**
 * Plugin Name: Contact Form Builder
 * Plugin URI: http://web-dorado.com/products/wordpress-contact-form-builder.html
 * Description: Contact Form Builder is an advanced plugin to add contact forms into your website. It comes along with multiple default templates which can be customized.
 * Version: 1.0.16
 * Author: WebDorado
 * Author URI: http://web-dorado.com/
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
define('WD_CFM_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('WD_CFM_URL', plugins_url(plugin_basename(dirname(__FILE__))));

// Plugin menu.
function contact_form_maker_options_panel() {
  add_menu_page('CForm Builder', 'CForm Builder', 'manage_options', 'manage_cfm', 'contact_form_maker', WD_CFM_URL . '/images/contact_form_maker_logo16.png');

  $manage_page = add_submenu_page('manage_cfm', 'Manager', 'Manager', 'manage_options', 'manage_cfm', 'contact_form_maker');
  add_action('admin_print_styles-' . $manage_page, 'contact_form_maker_manage_styles');
  add_action('admin_print_scripts-' . $manage_page, 'contact_form_maker_manage_scripts');

  $submissions_page = add_submenu_page('manage_cfm', 'Submissions', 'Submissions', 'manage_options', 'submissions_cfm', 'contact_form_maker');

  $blocked_ips_page = add_submenu_page('manage_cfm', 'Blocked IPs', 'Blocked IPs', 'manage_options', 'blocked_ips_cfm', 'contact_form_maker');
  add_action('admin_print_styles-' . $blocked_ips_page, 'contact_form_maker_manage_styles');
  add_action('admin_print_scripts-' . $blocked_ips_page, 'contact_form_maker_manage_scripts');

  $themes_page = add_submenu_page('manage_cfm', 'Themes', 'Themes', 'manage_options', 'themes_cfm', 'contact_form_maker');

  $licensing_plugins_page = add_submenu_page('manage_cfm', 'Licensing/Donation', 'Licensing/Donation', 'manage_options', 'licensing_cfm', 'contact_form_maker');
  add_action('admin_print_styles-' . $licensing_plugins_page, 'contact_form_maker_licensing_styles');

  add_submenu_page('manage_cfm', 'Featured Plugins', 'Featured Plugins', 'manage_options', 'featured_plugins_cfm', 'cfm_featured');

  $uninstall_page = add_submenu_page('manage_cfm', 'Uninstall', 'Uninstall', 'manage_options', 'uninstall_cfm', 'contact_form_maker');
  add_action('admin_print_styles-' . $uninstall_page, 'contact_form_maker_styles');
  add_action('admin_print_scripts-' . $uninstall_page, 'contact_form_maker_scripts');
}
add_action('admin_menu', 'contact_form_maker_options_panel');

function contact_form_maker() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_CFM_DIR . '/framework/WDW_CFM_Library.php');
  $page = WDW_CFM_Library::get('page');
  if (($page != '') && (($page == 'manage_cfm') || ($page == 'submissions_cfm') || ($page == 'blocked_ips_cfm') || ($page == 'themes_cfm') || ($page == 'licensing_cfm') || ($page == 'uninstall_cfm') || ($page == 'CFMShortcode'))) {
    require_once (WD_CFM_DIR . '/admin/controllers/CFMController' . ucfirst(strtolower($page)) . '.php');
    $controller_class = 'CFMController' . ucfirst(strtolower($page));
    $controller = new $controller_class();
    $controller->execute();
  }
}

function cfm_featured() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_CFM_DIR . '/featured/featured.php');
  wp_register_style('cfm_featured', WD_CFM_URL . '/featured/style.css', array(), get_option("wd_contact_form_maker_version"));
  wp_print_styles('cfm_featured');
  spider_featured('contact-form-builder');
}

add_action('wp_ajax_ContactFormMakerPreview', 'contact_form_maker_ajax');
add_action('wp_ajax_ContactFormmakerwdcaptcha', 'contact_form_maker_ajax'); // Generete captcha image and save it code in session.
add_action('wp_ajax_nopriv_ContactFormmakerwdcaptcha', 'contact_form_maker_ajax'); // Generete captcha image and save it code in session for all users.

function contact_form_maker_ajax() {
  require_once(WD_CFM_DIR . '/framework/WDW_CFM_Library.php');
  $page = WDW_CFM_Library::get('action');
  if ($page != 'ContactFormmakerwdcaptcha') {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
  }
  if ($page != '') {
    require_once (WD_CFM_DIR . '/admin/controllers/CFMController' . ucfirst($page) . '.php');
    $controller_class = 'CFMController' . ucfirst($page);
    $controller = new $controller_class();
    $controller->execute();
  }
}

// Add the Contact Form Builder button.
function contact_form_maker_add_button($buttons) {
  array_push($buttons, "contact_form_maker_mce");
  return $buttons;
}

// Register Contact Form Builder button.
function contact_form_maker_register($plugin_array) {
  $url = WD_CFM_URL . '/js/contact_form_maker_editor_button.js';
  $plugin_array["contact_form_maker_mce"] = $url;
  return $plugin_array;
}

function contact_form_maker_admin_ajax() {
  ?>
  <script>
    var contact_form_maker_admin_ajax = '<?php echo add_query_arg(array('action' => 'CFMShortcode'), admin_url('admin-ajax.php')); ?>';
    var contact_form_maker_plugin_url = '<?php echo WD_CFM_URL; ?>';
  </script>
  <?php
}
add_action('admin_head', 'contact_form_maker_admin_ajax');

function cfm_do_output_buffer() {
  ob_start();
}
add_action('init', 'cfm_do_output_buffer');

function contact_form_maker_frontend_main($content) {
  global $cfm_generate_action;
  if ($cfm_generate_action) {
    $pattern = '[\[Contact_Form_Builder id="([0-9]*)"\]]';
    $count_forms_in_post = preg_match_all($pattern, $content, $matches_form);
    if ($count_forms_in_post) {
      require_once (WD_CFM_DIR . '/frontend/controllers/CFMControllerForm_maker.php');
      $controller = new CFMControllerForm_maker();
      for ($jj = 0; $jj < $count_forms_in_post; $jj++) {
        $padron = $matches_form[0][$jj];
        $replacment = $controller->execute($matches_form[1][$jj]);
        $content = str_replace($padron, $replacment, $content);
      }
    }
  }
  return $content;
}
add_filter('the_content', 'contact_form_maker_frontend_main', 5000);

function cfm_shortcode($attrs) {
  $new_shortcode = '[Contact_Form_Builder';
  foreach ($attrs as $key=>$value) {
    $new_shortcode .= ' ' . $key . '="' . $value . '"';
  }
  $new_shortcode .= ']';
  return $new_shortcode;
}
add_shortcode('Contact_Form_Builder', 'cfm_shortcode');


$cfm_generate_action = 0;
function cfm_generate_action() {
  global $cfm_generate_action;
  $cfm_generate_action = 1;
}
add_filter('wp_head', 'cfm_generate_action', 10000);

// Add the Contact Form Builder button to editor.
add_action('wp_ajax_CFMShortcode', 'contact_form_maker_ajax');
add_filter('mce_external_plugins', 'contact_form_maker_register');
add_filter('mce_buttons', 'contact_form_maker_add_button', 0);

// Contact Form Builder Widget.
if (class_exists('WP_Widget')) {
  require_once(WD_CFM_DIR . '/admin/controllers/CFMControllerWidget.php');
  add_action('widgets_init', create_function('', 'return register_widget("CFMControllerWidget");'));
}

// Activate plugin.
function contact_form_maker_activate() {
  $version = get_option("wd_contact_form_maker_version");
  $new_version = '1.0.16';
  if ($version && version_compare($version, $new_version, '<')) {
    require_once WD_CFM_DIR . "/contact-form-builder-update.php";
    contact_form_maker_update($version);
    update_option("wd_contact_form_maker_version", $new_version);
  }
  else {
    require_once WD_CFM_DIR . "/contact-form-builder-insert.php";
    contact_form_maker_insert();
    add_option("wd_contact_form_maker_version", $new_version, '', 'no');
  }
}
register_activation_hook(__FILE__, 'contact_form_maker_activate');

if (!isset($_GET['action']) || $_GET['action'] != 'deactivate') {
  add_action('admin_init', 'contact_form_maker_activate');
}

// Contact Form Builder manage page styles.
function contact_form_maker_manage_styles() {
  $version = get_option("wd_contact_form_maker_version");
  wp_admin_css('thickbox');
  wp_enqueue_style('contact_form_maker_tables', WD_CFM_URL . '/css/contact_form_maker_tables.css', array(), $version);
  wp_enqueue_style('contact_form_maker_first', WD_CFM_URL . '/css/contact_form_maker_first.css', array(), $version);
  wp_enqueue_style('jquery-ui', WD_CFM_URL . '/css/jquery-ui-1.10.3.custom.css');
  wp_enqueue_style('contact_form_maker_style', WD_CFM_URL . '/css/style.css', array(), $version);
  wp_enqueue_style('contact_form_maker_codemirror', WD_CFM_URL . '/css/codemirror.css');
  wp_enqueue_style('contact_form_maker_layout', WD_CFM_URL . '/css/contact_form_maker_layout.css', array(), $version);
}
// Contact Form Builder manage page scripts.
function contact_form_maker_manage_scripts() {
  $version = get_option("wd_contact_form_maker_version");
  wp_enqueue_script('thickbox');
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-widget');

  wp_enqueue_script('gmap_form_api', 'https://maps.google.com/maps/api/js?sensor=false');
  wp_enqueue_script('gmap_form', WD_CFM_URL . '/js/if_gmap_back_end.js');

  wp_enqueue_script('contact_form_maker_admin', WD_CFM_URL . '/js/contact_form_maker_admin.js', array(), $version);
  wp_enqueue_script('contact_form_maker_manage', WD_CFM_URL . '/js/contact_form_maker_manage.js', array(), $version);

  wp_enqueue_script('contactformmaker', WD_CFM_URL . '/js/contactformmaker.js', array(), $version);

  wp_enqueue_script('contact_form_maker_codemirror', WD_CFM_URL . '/js/layout/codemirror.js', array(), '2.3');
  wp_enqueue_script('contact_form_maker_clike', WD_CFM_URL . '/js/layout/clike.js', array(), '1.0.0');
  wp_enqueue_script('contact_form_maker_formatting', WD_CFM_URL . '/js/layout/formatting.js', array(), '1.0.0');
  wp_enqueue_script('contact_form_maker_css', WD_CFM_URL . '/js/layout/css.js', array(), '1.0.0');
  wp_enqueue_script('contact_form_maker_javascript', WD_CFM_URL . '/js/layout/javascript.js', array(), '1.0.0');
  wp_enqueue_script('contact_form_maker_xml', WD_CFM_URL . '/js/layout/xml.js', array(), '1.0.0');
  wp_enqueue_script('contact_form_maker_php', WD_CFM_URL . '/js/layout/php.js', array(), '1.0.0');
  wp_enqueue_script('contact_form_maker_htmlmixed', WD_CFM_URL . '/js/layout/htmlmixed.js', array(), '1.0.0');
}

function contact_form_maker_styles() {
  wp_enqueue_style('contact_form_maker_tables', WD_CFM_URL . '/css/contact_form_maker_tables.css', array(), get_option("wd_contact_form_maker_version"));
}
function contact_form_maker_scripts() {
  wp_enqueue_script('contact_form_maker_admin', WD_CFM_URL . '/js/contact_form_maker_admin.js', array(), get_option("wd_contact_form_maker_version"));
}

function contact_form_maker_licensing_styles() {
  wp_enqueue_style('ontact_form_maker_licensing', WD_CFM_URL . '/css/contact_form_maker_licensing.css');
}

function contact_form_maker_front_end_scripts() {
  $version = get_option("wd_contact_form_maker_version");
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-widget');
  wp_enqueue_script('jquery-effects-shake');

  wp_enqueue_style('jquery-ui', WD_CFM_URL . '/css/jquery-ui-1.10.3.custom.css');
  wp_enqueue_style('contact_form_maker_frontend', WD_CFM_URL . '/css/contact_form_maker_frontend.css');

  wp_enqueue_script('gmap_form_api', 'https://maps.google.com/maps/api/js?sensor=false');
  wp_enqueue_script('gmap_form', WD_CFM_URL . '/js/if_gmap_front_end.js');

  wp_enqueue_script('cfm_main_front_end', WD_CFM_URL . '/js/cfm_main_front_end.js', array(), $version);
}
add_action('wp_enqueue_scripts', 'contact_form_maker_front_end_scripts');

// Languages localization.
function contact_form_maker_language_load() {
  load_plugin_textdomain('contact_form_maker', FALSE, basename(dirname(__FILE__)) . '/languages');
}
add_action('init', 'contact_form_maker_language_load');

?>