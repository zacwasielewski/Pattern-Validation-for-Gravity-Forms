<?php
/*
Plugin Name: Pattern Validation for Gravity Forms
Plugin URI: http://waz.ski
Description: A simple add-on to demonstrate the use of the Add-On Framework
Version: 0.1
Author: Zac Wasielewski
Author URI: http://waz.ski
*/

define( 'GF_PATTERN_VALIDATION_VERSION', '0.1' );

add_action( 'gform_loaded', array( 'GF_Pattern_Validation_Bootstrap', 'load' ), 5 );

class GF_Pattern_Validation_Bootstrap {

  public static function load() {
    if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
      return;
    }
    require_once( 'class-gfpatternvalidation.php' );
    GFAddOn::register( 'GFPatternValidation' );
  }
}

function gf_pattern_validation() {
  return GFPatternValidation::get_instance();
}
