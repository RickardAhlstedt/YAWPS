<?php
/**
 * YAWPS
 * @link https://github.com/Dronki/YAWPS
 * @since 1.0.0
 * @package YAWPS
 * 
 * @wordpress-plugin
 * Plugin Name: YAWPS
 * Plugin URI: https://github.com/Dronki/YAWPS
 * Description: Skeleton
 * Version: 1.0.0
 * Author: Rickard Ahlstedt
 * Author URI: https://github.com/Dronki/YAWPS
 * Developer: Rickard Ahlstedt
 * Developer URI: https://github.com/Dronki/YAWPS
 * Text Domain: pluginDomain
 * Domain Path: /languages
 *
 * WC requires at least: 2.2
 * WC tested up to: 2.3
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if( !defined('WPINC') ) {
    die;
}

require_once 'inc/clAutoloader.php';

if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    define( 'YAWPS', dirname(__FILE__) );
    
    load_plugin_textdomain( 'YAWPS', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
    
    $oAutoloader = new YAWPS\inc\clAutoloader();
    $oAutoloader->register();
    $oAutoloader->addNamespace( "YAWPS", trailingslashit(dirname(__FILE__) . '/') );
    
    global $oRegistry;
    $oRegistry = new YAWPS\inc\clRegistry();
    $oRegistry::set( 'clAutoloader', $oAutoloader );
    
    $oMain = new YAWPS\classes\clMainPlugin();
    $oRegistry::set( 'clMain', $oMain );
    
}