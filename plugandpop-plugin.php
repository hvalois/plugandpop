<?php
/*
Plugin Name: PlugAndPop
Plugin URI: http://hvalois.umacidade.net/
Description: Add a custom pop-up at page load
Author: Hebert Valois
Author URI: http://hvalois.umacidade.net/
*/

function plugandpop_enqueue_style() {
    wp_enqueue_style( 'plugandpop-css', plugins_url('includes/plugandpop-styles.css', __FILE__ ), false );
}
 
function plugandpop_enqueue_script() {
    wp_enqueue_script( 'plugandpop-js', plugins_url('includes/plugandpop-script.js', __FILE__ ), false );

    $options = get_option( 'plugandpop_settings' );

    $scriptData = array(
        'border' => $options['border'],
    );

    wp_localize_script('plugandpop-js', 'pp_options', $scriptData);
}
 
add_action( 'wp_enqueue_scripts', 'plugandpop_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'plugandpop_enqueue_script' );


load_plugin_textdomain('plugandpop');


add_action('admin_init', 'plugandpop_options_init' );
add_action('admin_menu', 'plugandpop_options_add_page');

// Init plugin options to white list our options
function plugandpop_options_init(){
	register_setting( 'plugandpop_settings_options', 'plugandpop_settings', 'plugandpop_options_validate' );
}

// Add menu page
function plugandpop_options_add_page() {
	add_options_page('PlugAndPop Options', 'PlugAndPop Options', 'manage_options', 'plugandpop_options', 'plugandpop_options_do_page');
}

// Draw the menu page itself
function plugandpop_options_do_page() {
	?>
	<div class="wrap">
		<h2>PlugAndPop Options</h2>
		<form method="post" action="options.php">
			<?php settings_fields('plugandpop_settings_options'); ?>
			<?php $options = get_option('plugandpop_settings'); ?>
			<table class="form-table">
				<tr valign="top"><th scope="row">Active (uncheck to disable)</th>
					<td><input name="plugandpop_settings[active]" type="checkbox" value="1" <?php checked('1', $options['active']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row">Only on home page (uncheck to disable)</th>
					<td><input name="plugandpop_settings[homeonly]" type="checkbox" value="1" <?php checked('1', $options['homeonly']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row">Link to:</th>
					<td><input type="text" name="plugandpop_settings[linkto]" size="45" value="<?php echo $options['linkto']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row">Image to show:</th>
					<td><input type="text" name="plugandpop_settings[image]" size="45" value="<?php echo $options['image']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row">Border width (px):</th>
					<td><input type="text" name="plugandpop_settings[border]" size="10" value="<?php echo $options['border']; ?>" /></td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php	
}

function plugandpop_options_validate($input) {
	// Either 0 or 1
	$input['active'] = ( $input['active'] == 1 ? 1 : 0 );
	$input['homeonly'] = ( $input['homeonly'] == 1 ? 1 : 0 );	
	
	// Safe text with no HTML tags
	$input['linkto'] =  wp_filter_nohtml_kses($input['linkto']);
	
	$input['image'] =  wp_filter_nohtml_kses($input['image']);
	
    return $input;
}

function create_plugandpop() {

	$options = get_option('plugandpop_settings', '');
	$active = $options['active'];
	$linkto = $options['linkto'];
	$image = $options['image'];
	$border = $options['border'];
	$homeonly = $options['homeonly'];

    echo '<div id="boxes">
		<div style="padding: ' . $border . 'px; display: none; opacity: 0;" id="dialog" class="frame"> 
			<div class="close" onclick="hide_popup()"></div>
			<div id="pop" style="">
				<a href="' . $linkto . '">
					<img src="' . $image . '" class="banner">
				</a>
			</div>
		</div>
		<div style="width: 100vw; height: 100vh; display: block; opacity: 0" id="mask" onclick="hide_popup()"></div>
	</div>';
}

function set_plugandpop() {
	$options = get_option('plugandpop_settings');

		if ( $options['homeonly'] == 1 ) {
			if ( is_front_page() && $options['active'] == 1 ) {
		 		create_plugandpop();
			}
		} else {
			if ( !is_admin() && $options['active'] == 1 ) {
		 		create_plugandpop();
		}
	}
} add_action ('wp', 'set_plugandpop');

?>