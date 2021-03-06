<?php
/**
 * @package Business Address
 */
/*
Plugin Name: Business Address
Plugin URI: http://markszymanski.com/
Description: There are no perfect ways to store this information in one place in your site, and to display it in many places. Some put it in the Theme Customizer, but this isn't the right place for this. Creating a widget is another approach but I find my self using them less and less. 
Version: 0.2
Author: Mark Szymanski
Author URI: https://github.com/szyam/business-address
License: GPLv2
Text Domain: business-address
*/

add_action( 'admin_menu', 'bus_add_menu' );

function bus_add_menu() {
    add_options_page( 'Business Address Fields', 'Business Address', 'manage_options', 'bus_add-identifier', 'bus_add_options' );
}

function bus_add_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   return;
    }
?>
<div class="wrap">

    <h2><?php _e( 'Address Fields', 'bus_add-plugin' ) ?></h2>

    <form method="post" action="options.php">
        <?php settings_fields( 'bus_add-settings-group' ); ?>
        <?php do_settings_sections( 'bus_add-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Address</th>
                <td><input type="text" name="bus_address" value="<?php echo esc_attr( get_option('bus_address') ); ?>" /></td>
            </tr>
             
            <tr valign="top">
                <th scope="row">City</th>
                <td><input type="text" name="bus_city" value="<?php echo esc_attr( get_option('bus_city') ); ?>" /></td>
            </tr>
            
            <tr valign="top">
                <th scope="row">State</th>
                <td><input type="text" name="bus_state" value="<?php echo esc_attr( get_option('bus_state') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Zip Code</th>
                <td><input type="text" name="bus_zip" value="<?php echo esc_attr( get_option('bus_zip') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Phone Number</th>
                <td><input type="text" name="bus_phone" value="<?php echo esc_attr( get_option('bus_phone') ); ?>" /></td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
     
    </form>
</div>
<?php
}

add_action( 'admin_init', 'bus_add_settings' );

function bus_add_settings() {
    register_setting( 'bus_add-settings-group', 'bus_address' );
    register_setting( 'bus_add-settings-group', 'bus_city' );
    register_setting( 'bus_add-settings-group', 'bus_state' );
    register_setting( 'bus_add-settings-group', 'bus_zip' );
    register_setting( 'bus_add-settings-group', 'bus_phone' );
}

// Add Shortcode for full addy
function bus_add_shortcode() {
    $bus_add_block = '<span class="bus-address-container" itemscope itemtype="http://schema.org/LocalBusiness">';
    $bus_add_block .= '<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';
    $bus_add_block .= '<span class="bus-address" itemprop="streetAddress">'.esc_attr( get_option('bus_address')).'</span>, ';
    $bus_add_block .= '<span class="bus-city" itemprop="addressLocality">'.esc_attr( get_option('bus_city')).'</span>, ';
    $bus_add_block .= '<span class="bus-state" itemprop="addressRegion">'.esc_attr( get_option('bus_state')).'</span> ';
    $bus_add_block .= '<span class="bus-zip" itemprop="postalCode">'.esc_attr( get_option('bus_zip')).'</span> ';
    if((esc_attr( get_option('bus_phone') ))) {
        $bus_add_block .= '- <span class="bus-phone" itemprop="telephone"><a href="tel:'.preg_replace("/[^0-9]/","",esc_attr( get_option('bus_phone'))).'">'.esc_attr( get_option('bus_phone') ).'</a></span>';
    }
    $bus_add_block .= '</span></span><!-- Closing spans -->';
    return $bus_add_block;
}

add_shortcode( 'business_address', 'bus_add_shortcode' );

// Telephone
function bus_add_phone_shortcode() {
    return '<span class="bus-phone" itemprop="telephone"><a href="tel:'.preg_replace("/[^0-9]/","",esc_attr( get_option('bus_phone'))).'">'.esc_attr( get_option('bus_phone') ).'</a></span>';
}

add_shortcode( 'business_address_phone', 'bus_add_phone_shortcode' );
