<?php
/**
 * Link shortcode
 *
 * Write [link] in your post editor to render this shortcode.
 *
 * @package	 ABS
 * @since    1.0.0
 */

if ( ! function_exists( 'chart_demo' ) ) {
	// Add the action.
	add_action( 'plugins_loaded', function() {
		// Add the shortcode.
		add_shortcode( 'statosio-demo', 'chart_demo' );
	});

	/**
	 * Shortcode Function
	 *
	 * @param  Attributes $atts l|t URL TEXT.
	 * @return string
	 * @since  1.0.0
	 */
	function chart_demo( $atts ) {

		$_return = "<div id=\"d3_statosio\"></div>
		<script>console.log(\"test\")</script>
		<script src=\"https://cdnjs.cloudflare.com/ajax/libs/d3/6.2.0/d3.js\"></script>
		<script src=\"https://cdnjs.cloudflare.com/ajax/libs/statosio/0.9/statosio.js\"></script>
		<script>
        d3.json( \"https://d3.statosio.com/data/performance.json\" )
            .then( ( file ) => {
                d3.statosio( 
                    file, 
                    \"name\", 
                    [\"mobile\",\"desktop\"],
                    {\"showTitle\":true}
                )
            } )
    	</script>";

        return $_return;
	}
} // End if().



