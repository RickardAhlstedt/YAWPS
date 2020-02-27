<?php

namespace YAWPS\classes;

class clMainPlugin {
    
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'registerNavigation' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'registerScriptAssets' ) );
    }
    
    public function registerNavigation() {
       
    }
 
    public function registerScriptAssets() {
        wp_enqueue_script(
            'jQuery3',
            plugins_url( 'woostock/assets/modules/jQuery/jquery-3.4.1.min.js' ), 
            array(), 
            "3.4"
        );
    }
    
}
