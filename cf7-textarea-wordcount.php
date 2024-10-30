<?php
/*
Plugin Name: Contact Form 7 Textarea Wordcount 
Plugin URI: http://www.ninthlink.com/2010/12/30/contact-form-7-textarea-wordcount/
Description: Adds an option to Contact Form 7's Textarea field for a Max Wordcount, and limits input to max wordcount on the front end.
Author: Alex Chousmith
Version: 1.1.1
Author URI: http://www.ninthlink.com/
*/

/**
 * overwrite Contact Form 7's base module for [textarea] and [textarea*]
 */

// Shortcode handler, overwriting the default cf7 version
function wpcf7wc_textarea_shortcode_handler( $tag ) {
	$tag = new WPCF7_Shortcode( $tag );

	if ( empty( $tag->name ) )
		return '';

	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type );

	if ( $validation_error )
		$class .= ' wpcf7-not-valid';

	$atts = array();

	$atts['cols'] = $tag->get_cols_option( '40' );
	$atts['rows'] = $tag->get_rows_option( '10' );
	$atts['maxlength'] = $tag->get_maxlength_option();
	$atts['minlength'] = $tag->get_minlength_option();

	if ( $atts['maxlength'] && $atts['minlength'] && $atts['maxlength'] < $atts['minlength'] ) {
		unset( $atts['maxlength'], $atts['minlength'] );
	}

	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();
	$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );
  
  // add our maxwc
  $atts['data-maxwc'] = $tag->get_option( 'maxwc', 'int', true );
  
	if ( $tag->has_option( 'readonly' ) ) {
		$atts['readonly'] = 'readonly';
	}

	if ( $tag->is_required() ) {
		$atts['aria-required'] = 'true';
	}

	$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

	$value = empty( $tag->content )
		? (string) reset( $tag->values )
		: $tag->content;

	if ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) {
		$atts['placeholder'] = $value;
		$value = '';
	}

	$value = $tag->get_default_option( $value );

	$value = wpcf7_get_hangover( $tag->name, $value );

	$atts['name'] = $tag->name;
  
  // inject our word counter
	if( $atts['data-maxwc'] ) {
    $validation_error .= '<span class="wpcf7wc-msg"><br /><br /><input type="text" name="wcount_'. $atts['name'] .'" id="wcount_'. $atts['name'] .'" size="3" maxlength="'. ( $atts['data-maxwc'] % 10 ) .'" style="text-align:center; width: auto" value="" readonly="readonly" /> words. Please limit to '. $atts['data-maxwc'] .' words or less.</span>';
  }
  
	$atts = wpcf7_format_atts( $atts );
  
	$html = sprintf(
		'<span class="wpcf7-form-control-wrap %1$s"><textarea %2$s>%3$s</textarea>%4$s</span>',
		sanitize_html_class( $tag->name ), $atts,
		esc_textarea( $value ), $validation_error );
  
	return $html;
}

// Validation filter re adjusting hooks
function wpcf7wc_undo_hooks( $length ) {
  remove_action( 'wpcf7_admin_init', 'wpcf7_add_tag_generator_textarea', 20 );
}
add_action( 'wpcf7_admin_init', 'wpcf7wc_undo_hooks', 1 );

function wpcf7wc_add_shortcode_textarea() {
  if ( function_exists('wpcf7_remove_shortcode') ) {
    wpcf7_remove_shortcode( 'textarea' );
  }
	wpcf7_add_shortcode( array( 'textarea', 'textarea*' ),
		'wpcf7wc_textarea_shortcode_handler', true );
}
// add our remove+add, but with priority After the "textarea" one
add_action( 'wpcf7_init', 'wpcf7wc_add_shortcode_textarea', 20 );

function wpcf7wc_enqueue_scripts() {
	$plugin_dir = trailingslashit(plugins_url(basename(dirname(__FILE__)))); 
	wp_enqueue_script( 'wpcf7wc', $plugin_dir .'wpcf7wc.js',
		array( 'jquery', 'contact-form-7' ), '1.1', true );
}
add_action( 'wpcf7_enqueue_scripts', 'wpcf7wc_enqueue_scripts' );

// Tag generator

add_action( 'wpcf7_admin_init', 'wpcf7wc_add_tag_generator_textarea', 20 );

function wpcf7wc_add_tag_generator_textarea() {
  if ( class_exists( 'WPCF7_TagGenerator' ) ) {
    $tag_generator = WPCF7_TagGenerator::get_instance();
    $tag_generator->add( 'textarea', __( 'text area', 'contact-form-7' ),
      'wpcf7wc_tag_generator_textarea' );
  }
}

function wpcf7wc_tag_generator_textarea( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );
	$type = 'textarea';

	$description = __( "Generate a form-tag for a multi-line text input field with optional Max Wordcount.", 'wpcf7wc' ) ." ". __("For more details on Text Fields in Contact Form 7, see %s.", 'wpcf7wc' );

	$desc_link = wpcf7_link( __( 'http://contactform7.com/text-fields/', 'contact-form-7' ), __( 'Text Fields', 'contact-form-7' ) );

?>
<div class="control-box">
<fieldset>
<legend><?php echo sprintf( esc_html( $description ), $desc_link ); ?></legend>

<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></th>
	<td>
		<fieldset>
		<legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></legend>
		<label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'contact-form-7' ) ); ?></label>
		</fieldset>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Default value', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /><br />
	<label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html( __( 'Use this text as the placeholder of the field', 'contact-form-7' ) ); ?></label></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
	</tr>

  <tr>
  <th scope="row"><label for=<?php echo esc_attr( $args['content'] . '-maxwc' ); ?>"><?php echo esc_html( __( 'Max Wordcount', 'wpcf7wc' ) ); ?></label></th>
  <td><input type="text" name="maxwc" class="numeric oneline option" id="<?php echo esc_attr( $args['content'] . '-maxwc' ); ?>" /></td>
  </tr>
</tbody>
</table>
</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>

	<br class="clear" />

	<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
</div>
<?php
}
