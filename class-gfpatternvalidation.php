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
		add_filter( 'gform_field_validation', array( $this, 'validate_field'), 10, 4 );
	}


	// # FRONTEND FUNCTIONS --------------------------------------------------------------------------------------------

	function validate_field( $result, $value, $form, $field ) {
		$valid = true;

		if ( $this->should_validate( $field->patternField ) ) {
			$valid = $this->validate( $field->patternField, $value );

			if ( $valid === false ) {
				$result['is_valid'] = false;
				$result['message']  = esc_html__( $field->label, 'patternvalidation' ) . ' is invalid.';
			}
		}

		return $result;
	}


	// # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------

	function field_advanced_settings( $position, $form_id ) {
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
	   $tooltips['form_field_pattern_value'] = "<h6>Validate Pattern</h6>Enter pattern to validate this field's input against. To use regular expression matching, wrap your pattern with forward slashes (example: /mypattern/)";
	   return $tooltips;
	}


	// # HELPERS -------------------------------------------------------------------------------------------------------

	function should_validate( $pattern = '' ) {
		//return isset($pattern) && trim($pattern) !== '';
		return trim($pattern) !== '';
	}

	function validate( $pattern, $value ) {
		if ( $this->is_regex( $pattern ) ) {
			return $this->validate_regex( $pattern, $value);
		} else {
			return $this->validate_string( $pattern, $value);
		}
	}

	function validate_regex( $pattern, $value ) {
 		return preg_match( $pattern, $value ) === 1;
	}

	function validate_string( $string, $value ) {
		return $string === $value;
	}

	function is_regex( $str = '' ) {
		$chars = str_split($str);
		$first = array_shift($chars);
		$last  = array_pop($chars);
		return ($first . $last) === '//';
	}

}
