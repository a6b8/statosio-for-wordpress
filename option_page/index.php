<?php
//register settings
function theme_options_add(){
    register_setting( 'theme_settings', 'theme_settings' );
}
 
//add settings page to menu
function add_options() {
    add_menu_page( __( 'Statosio' ), __( 'Statosio' ), 'manage_options', 'statosio', 'theme_options_page' );
}
//add actions
add_action( 'admin_init', 'theme_options_add' );
add_action( 'admin_menu', 'add_options' );
 
//start settings page
function theme_options_page() {
    
    if ( ! isset( $_REQUEST['updated'] ) )
    $_REQUEST['updated'] = false;
    
    //get variables outside scope
    global $color_scheme;
    ?>

    <style>
        #p134_textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        #p134_wrapper {
            padding-left : 20px;
            padding-right : 20px;
            width : 640px;
            overflow : hidden";
        }

        .p134_statosio {
            height : 300px;
            width : 600px;
            overflow : hidden;
            background-color : white;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/6.2.0/d3.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/statosio/0.9/statosio.js"></script>

    <div> 
        <form method="post" action="options.php">
            <?php settings_fields( 'theme_settings' ); ?>
            <?php $options = get_option( 'theme_settings' ); ?>
            <table>
                <div id="p134_wrapper">
                    <h2>Statosio Shortcode Generator</h2>
                    Statosio for Wordpress is based on <a href="https://github.com/a6b8/statosio.js">statosio.js</a> and helps to generate simple charts, with Wordpress [shortcodes]. 
                    <h3>Preview</h3>
                    <div id="d3_statosio" class="p134_statosio"></div>
                    <button class="btn" type="button" onclick="load_dataset()">Reload Dataset</button>
                    <span id="information">More Information <a href="https://d3.statosio.com/options/">https://d3.statosio.com/options/</a></span>
                    <br><br>
                    <div id="p134_inputs"></div>
                    <br><br>       
                    <h3>Shortcode</h3>
                    <textarea id="p134_code_wordpress" class="p134_textarea" name="example" rows="4" cols="50"></textarea>
                    <br>
                    <button name="copy" type="button" onclick="button_code_copy('wordpress')">Copy</button>
                    <br>
                    <br>
                </div>
            </table>
    </div><!-- END wrap -->
    
    <script>
    function validations() {
        function validation( key ) {
            var error = ''

            let value = document.getElementById( key ).value
            switch( key ) {
                case 'dataset':
                    if( struct['dataset']['content'] != null ) {
                        if( Array.isArray( struct['dataset']['content'] ) ) {
                            if( struct['dataset']['content'].length !== 0 ) {
                                if( typeof( struct['dataset']['content'][ 0 ] ) === 'object' ) {

                                } else {
                                    error = 'Dataset: Rows are not type of Object'
                                }
                            } else {
                                error = 'Dataset: No rows found.'
                            }
                        } else {
                            error = 'Dataset: Data Structure is not Array'
                        }
                    } else {
                        error = 'Dataset: Data could not loaded'
                    }

                    if( value !== struct['dataset']['url'] ) {
                        error = 'Dataset: Formdata is not in sync with Preview. Please reload Dataset.'
                    }
                    break;
                case 'y':
                    value === '' ? error = 'Y: Y Is empty' : ''
                    break;
                case 'x':
                    try {
                        JSON.parse( value )
                    } catch( e ) {
                        error = 'X: Array is not valid'
                    }

                    break;
                case 'options':
                    try {
                        JSON.parse( value )
                    } catch ( e ) {
                        error = 'Options: JSON is not valid'
                    }
                break;
            }

            return error
        }

        errors = []
        Object
            .keys( struct['elements'] )
            .forEach( ( key ) => {
                errors.push( validation( key ) )
            })

        struct['valid'] = errors.join('') === ''
        if( !struct['valid'] ) {
            html = 'Error(s):<br>'
            errors
                .forEach( ( error ) => {
                    error.length > 0 ? html += '- ' + error + '<br>' : ''
                })
            document.getElementById('d3_statosio').innerHTML = html
        }
    }


    function preview_chart() {
        function statosio( _dataset, _y, _x, _options ) {
            try {
                d3.statosio( 
                    _dataset, 
                    _y, 
                    JSON.parse( decodeURIComponent( _x ) ), 
                    JSON.parse( decodeURIComponent( _options ) )
                ) 
            } catch( e ) {
                document.getElementById('d3_statosio').innerHTML = 'Some values or keys are not correct.'
            }
            
        }

        function shortcode_parse() {
            result = {}
            shortcode = document.getElementById('p134_code_wordpress').value

            start = "[statosio "

            str = shortcode.substring( start.length, shortcode.length-1)
            str
                .split(' ')
                .forEach( ( kv ) => {
                    item = kv.split('=')
                    result[ item[ 0 ] ] = item[ 1 ].substring( 1,item[ 1 ].length-1)
            } )
            return result
        }

        
        params = shortcode_parse()


        let url = document.getElementById('dataset').value
        if( struct['valid'] ) {
            document.getElementById('d3_statosio').innerHTML = ''
            statosio( struct['dataset']['content'], params['y'], params['x'], params['options'] )   
            validations()
        } else {
            //document.getElementById('d3_statosio').innerHTML = 'Can not show preview. Please read Validation Error(s) below.'
        }
    }


    function button_code_copy( key ) {
        str = 'p134_code_' + key
        let textarea = document.getElementById( str )
        textarea.select()
        document.execCommand( "copy" )
    }


    function load_dataset() {
        function get_dataset( url, callback ) {
            var xhr = new XMLHttpRequest()
            xhr.open( 'GET', url, true )
            xhr.responseType = 'json'
            xhr.onload = function() {
                var status = xhr.status
                if ( status === 200 ) {
                    callback( null, xhr.response )
                } else {
                    callback( status, xhr.response )
                }
            }
            xhr.onerror = function( e ) {
                callback( 404, xhr.response )
            };

            xhr.send()
        }

        struct['dataset']['url'] = document.getElementById('dataset').value
        
        get_dataset(
            struct['dataset']['url'],
            function( err, data ) {
                if ( err !== null ) {
                    struct['dataset']['content'] = null
                    validations()
                } else {
                    struct['dataset']['content'] = data
                    validations()
                    preview_chart()
                }
            }
        )
    }

    function codes() {
        function wordpress() {
            function value_prepare( key, value ) {
                switch( key ) {
                    case 'x':
                        value = encodeURIComponent( value )
                        break;
                    case 'options':
                        list = [ '{', '}' ]

                        if( value === '' || value === '{}' ) {
                            value = '{}'
                        }
                        value = encodeURIComponent( value )
                        break;
                }
                return value
            }

            
            let p = ''
            p += '[statosio '
            p += Object
                .keys( struct['elements'] )
                .map( ( key ) => {
                    value = document.getElementById( key ).value 
                    value = value_prepare( key, value )
                    str = ''
                    str += key
                    str += '="'
                    str += value
                    str += '"'
                    return str
                })
                .join( ' ' )
            p += ']'

            document.getElementById( 'p134_code_wordpress' ).value = p

            return true
        }



        function javascript() {
            let template = 
            "<!DOCTYPE html>\n" + 
            "<head>\n" +
            "\t<title>Statosio - Javascript Sandbox Demo</title>\n" + 
            "\t<meta content=\"text/html;charset=utf-8\" http-equiv=\"Content-Type\">\n" + 
            "\t<meta content=\"utf-8\" http-equiv=\"encoding\">\n" + 
            "\t<script src=\"https:\/\/cdnjs.cloudflare.com\/ajax\/libs\/d3\/6.2.0\/d3.js\"><\/script>\n" + 
            "\t<script src=\"https:\/\/cdnjs.cloudflare.com\/ajax\/libs\/statosio\/0.9\/statosio.js\"><\/script>\n" + 
            "<\/head>\n" + 
            "<body>\n" + 
            "\t<script>\n" + 
            "\t\/\/ Load Sample Dataset\n" + 
            "\t\td3.json( \"<<--dataset-->>\" )\n" +
            "\t\t\t.then( ( file ) => {\n" +
            "\t\t\t\t// Generate chart\n" + 
            "\t\t\t\t\td3.statosio(\n" + 
            "\t\t\t\t\tfile,\n" +
            "\t\t\t\t\t\"<<--y-->>\",\n" +
            "\t\t\t\t\t<<--x-->>,\n" +
            "\t\t\t\t\t<<<--options-->>\n" +
            "\t\t\t\t\t)\n" + 
            "\t\t\t\t} )\n" +
            "\t\t<\/script>\n" + 
            "<\/body>\n"

            Object
                .keys( struct['elements'] )
                .map( ( key ) => {
                    value = document.getElementById( key ).value 
                    template = template.replace( '<<--' + key + '-->>', value)
                    return str
                } )

            document.getElementById( 'p134_code_javascript' ).value = template

            return true
        }


        function ruby() {
            function value_prepare( key, value ) {
                switch( key ) {
                    case 'options':
                        value = value.replaceAll( '":', '" =>' )
                        value = JSON.stringify( value )
                        break;
                }
                return value
            }

            let template = 
                "require 'statosio'\n" + 
                "require 'open-uri'\n" + 
                "require 'json'\n" + 
                "\n" + 
                "# Initialize Statosio\n" + 
                "statosio = Statosio::Generate.new\n" + 
                "\n" + 
                "# Load Sample Dataset\n" + 
                "url = '<<--dataset-->>'\n" + 
                "content = URI.open( url ).read\n" +
                "dataset = JSON.parse( content )\n" +
                "\n" + 
                "# Generate chart as .svg\n" +
                "\tchart = statosio.svg(\n" +
                "\tdataset: dataset,\n" + 
                "\tx: <<--x-->>,\n" +
                "\ty: '<<--y-->>',\n" +
                "\toptions: <<--options-->>\n" +
                ")\n"

            Object
                .keys( struct['elements'] )
                .map( ( key ) => {
                    value = document.getElementById( key ).value 
                    value = value_prepare( key, value )
                    template = template.replace( '<<--' + key + '-->>', value )
                    return str
                } )

            document.getElementById( 'p134_code_ruby' ).value = template + ""

            return true
        }

        wordpress()
        
        return true
    }

        var struct = {
            'elements' : {
                'dataset': {
                    'name': '<b>dataset</b>',
                    'default': 'https://d3.statosio.com/data/performance.json',
                },
                'y': {
                    'name': '<b>y</b>',
                    'default': 'name',
                },
                'x': {
                    'name': '<b>x</b>',
                    'default': '["mobile"]',
                }, 
                'options': {
                    'name': '<b>options</b>',
                    'default': '{ "styleColorSelectorsChart": ["#E2B08E", "#CC8074"],"styleColorCanvasBackground": "none","styleColorGridline": "#2F3138","styleStrokeGridline": 1,"showAverage": false}',
                }
            },
            'dataset': {
                'url' : null,
                'content' : null
            },
            'valid': false 
        }

        struct['dataset']['url'] = struct['elements']['dataset']['default']

        Object
            .keys( struct['elements'] )
            .forEach( ( key ) => {
                console.log( key )
                let main = document.getElementById( 'p134_inputs' )
                console.log( main)
                let headline = document.createElement( 'span' )
                headline.innerHTML = struct['elements'][ key ]['name']
                main.appendChild( headline )
                main.appendChild( document.createElement( 'br' ) )

                let input = document.createElement( 'input' )
                input.type = 'text'
                input.id = key
                input.className  = 'p134_textarea'
                input.value = struct['elements'][ key ]['default']
                main.appendChild( input )
                main.appendChild( document.createElement( 'br' ) )


                let error = document.createElement( 'span' )
                error.id = key + '_error'
                main.appendChild( error )
                document
                    .getElementById( key )
                    .addEventListener( 'input', function( value ) { 
                        validations()
                        codes()
                        preview_chart() 
                } )
        } )


        validations()
        codes()
        load_dataset()
    </script>

    <?php
}
?>