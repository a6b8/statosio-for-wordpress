<?php
/**
 * Link shortcode
 *
 * Write [link] in your post editor to render this shortcode.
 *
 * @package	 ABS
 * @since    1.0.0
 */

if ( ! function_exists( 'chart' ) ) {
	// Add the action.
	add_action( 'plugins_loaded', function() {
		// Add the shortcode.
		add_shortcode( 'statosio', 'chart' );
	});

	/**
	 * Shortcode Function
	 *
	 * @param  Attributes $atts l|t URL TEXT.
	 * @return string
	 * @since  1.0.0
	 */
	function chart( $atts ) {

		// Save $atts.
		$_atts = shortcode_atts( array(
			'dataset'  => "",
			'x'  => "",
			'y'  => "",
			'options'  => "",
		), $atts );

		// dataset
		$_dataset = $_atts['dataset'];

		// x
		$_x = $_atts['x'];

		// y
		$_y = $_atts['y'];
		
		// options
		$_options = $_atts['options'];

		$_return = "<div id=\"d3_statosio\"></div>
		<script>console.log(\"test\")</script>
		<script src=\"https://cdnjs.cloudflare.com/ajax/libs/d3/6.2.0/d3.js\"></script>
		<script src=\"https://cdnjs.cloudflare.com/ajax/libs/statosio/0.9/statosio.js\"></script>
		<script>
        d3.json( \"{$_dataset}\" )
            .then( ( file ) => {
                d3.statosio( 
                    file, 
                    \"{$_x}\", 
                    \"{$_y}\",
                    {$_options}
                )
            } )
    	</script>";

		return $_return;
	}
} // End if().



