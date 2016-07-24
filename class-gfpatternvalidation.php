<?php

GFForms::include_addon_framework();

class GFPatternValidation extends GFAddOn {

	protected $_version = GF_PATTERN_VALIDATION_VERSION;
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'patternvalidation';
	protected $_path = 'patternvalidation/patternvalidation.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Pattern Validation for Gravity Forms';
	protected $_short_title = 'Pattern Validation';

	private static $_instance = null;

	/**
	 * Get an instance of this class.
	 *
	 * @return GFSimpleAddOn
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GFPatternValidation();
		}

		return self::$_instance;
	}

	/**
	 * Handles hooks and loading of language files.
	 */
	public function init() {
		parent::init();
		add_filter( 'gform_field_advanced_settings', array( $this, 'field_advanced_settings' ), 10, 2 );
		add_action( 'gform_editor_js', array( $this, 'editor_script' ));
		add_filter( 'gform_tooltips', array( $this, 'tooltips' ));
	}


	// # SCRIPTS & STYLES -----------------------------------------------------------------------------------------------

	/**
	 * Return the scripts which should be enqueued.
	 *
	 * @return array
	 */
	//public function scripts() {
	//	$scripts = array(
	//		array(
	//			'handle'  => 'my_script_js',
	//			'src'     => $this->get_base_url() . '/js/my_script.js',
	//			'version' => $this->_version,
	//			'deps'    => array( 'jquery' ),
	//			'strings' => array(
	//				'first'  => esc_html__( 'First Choice', 'simpleaddon' ),
	//				'second' => esc_html__( 'Second Choice', 'simpleaddon' ),
	//				'third'  => esc_html__( 'Third Choice', 'simpleaddon' )
	//			),
	//			'enqueue' => array(
	//				array(
	//					'admin_page' => array( 'form_settings' ),
	//					'tab'        => 'simpleaddon'
	//				)
	//			)
	//		),
	//
	//	);
	//
	//	return array_merge( parent::scripts(), $scripts );
	//}

	/**
	 * Return the stylesheets which should be enqueued.
	 *
	 * @return array
	 */
	//public function styles() {
	//	$styles = array(
	//		array(
	//			'handle'  => 'my_styles_css',
	//			'src'     => $this->get_base_url() . '/css/my_styles.css',
	//			'version' => $this->_version,
	//			'enqueue' => array(
	//				array( 'field_types' => array( 'poll' ) )
	//			)
	//		)
	//	);
	//
	//	return array_merge( parent::styles(), $styles );
	//}


	// # FRONTEND FUNCTIONS --------------------------------------------------------------------------------------------


	// # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------

	function field_advanced_settings( $position, $form_id ) {

    //create settings on position 50 (right after Admin Label)
    if ( $position == 100 ) {
      ?>
      <li class="pattern_setting field_setting" data-gf_display="list-item">
        <label for="field_pattern">
          <?php _e("Validate Pattern", "gravityforms"); ?>
          <?php gform_tooltip("form_field_pattern_value") ?>
        </label>
        <input type="text" id="field_pattern_value" onkeyup="SetFieldProperty('patternField', this.value)" />
      </li>
      <?php
    }
	}

	function editor_script() {
    ?>
    <script type="text/javascript">
			fieldSettings['text'] += ', .pattern_setting';
			fieldSettings['post_custom_field'] += ', .pattern_setting';

      jQuery(document).bind('gform_load_field_settings', function(event, field, form) {
				jQuery('#field_pattern_value').attr('value', field['patternField']);
      });
    </script>
    <?php
	}

	function tooltips( $tooltips ) {
	   $tooltips['form_field_pattern_value'] = "<h6>Validate Pattern</h6>Regex or pattern to validate against";
	   return $tooltips;
	}


	// # HELPERS -------------------------------------------------------------------------------------------------------

}
