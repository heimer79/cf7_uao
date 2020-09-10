<?php

/*
Plugin Name: Contact form 7 WordPress - Marketing Cloud
Plugin URI: https://www.uao.edu.co
Description: Plugin para integrar Contact form 7 con Marketing Cloud
Version: 2.0.0
Author: Heimer Martínez y Gustavo Torres
Author URI: https://www.uao.edu.co
License: GPL2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


//Evita que un usuario malintencionado ejecute codigo php desde la barra del navegador
defined('ABSPATH') or die( "Hasta la vista Baby" );

//Aqui se definen las constantes
define('cf7UAO_RUTA',plugin_dir_path(__FILE__));
define('cf7UAO_NOMBRE','cf7_UAO');

//Archivos externos
include(cf7UAO_RUTA.'/includes/opciones.php');


 define('API_URL_MARKETING_CLOUD_PROGRAMAS', 'https://cloud.crm.uao.edu.co/guardarInteresados');
 define('API_URL_MARKETING_CLOUD_EVENTOS', 'https://cloud.crm.uao.edu.co/guardarEventos');
 define('API_ERROR_MESSAGE', 'Lo sentimos, ocurrió un error vuelve a intentarlo.');
 //To make it working, we must need at least CF7-v5.0;
 add_action( 'wpcf7_before_send_mail', 'cf7_validate_api', 15, 3 );

 function cf7_validate_api($cf7, &$abort, $submission){


     $titulo = $cf7->title();


     global $nombre_formulario_de_programas_0;
     global $nombre_formulario_de_eventos_1;

     $patron1 = $nombre_formulario_de_programas_0;
     $patron2 = $nombre_formulario_de_eventos_1;

     $tit = preg_match("/($patron1|$patron2)/i", $titulo);

   

    if ($tit != 1 ) //CF7 post-id from admin settings;

    return;


     //$submission = WPCF7_Submission::get_instance();
     $postedData = $submission->get_posted_data();

     //-----API posting------
     if (preg_match("/$patron1/i", $titulo)){
            $url = API_URL_MARKETING_CLOUD_PROGRAMAS;
     } elseif(preg_match("/$patron2/i", $titulo)){
             $url = API_URL_MARKETING_CLOUD_EVENTOS;
     }


     $args = [
         'headers' => [
             'Accept' => 'application/json; charset=utf-8', // The API returns JSON
         ],
         'body' => $postedData
     ];
     $response = wp_remote_post( $url, $args );


     //------------------
     $msgs = $cf7->prop('messages');

     if( is_wp_error( $response ) ){
         $error_message = $response->get_error_message();
         $msgs['mail_sent_ng'] = API_ERROR_MESSAGE;

     } else {
         $response_body = wp_remote_retrieve_body( $response );
         $data = json_decode( $response_body );

         if( empty($data)){ //API validation error!
             $msgs['mail_sent_ng'] = API_ERROR_MESSAGE;
         }else{
             $msgs['mail_sent_ok'] = $data->message;
         }
     }

     $cf7->set_properties(array('messages' => $msgs));

     return $cf7;
     
   

 }





  
        add_action('wp_footer', 'ciudades');

        function ciudades(){

            

                $theme = wp_get_theme();
                if('UAO Theme' == $theme->name || 'UAO Theme' == $theme->parent_theme){
                    ?>
                        
                        <script defer>

                            //Get cities From Json File
                            const searchcity = async searchBox => {
                                // ejecutar fetch...
                                const res = await fetch('<?php echo plugin_dir_url( __FILE__ );?>municipios.json');


                                const cities = await res.json();

                                //Get Entered Data
                                let fits = cities.filter(city => {
                                    const regex = new RegExp(`^${searchBox}`, 'gi');
                                    return city.municipio.match(regex);

                                });

                                if (searchBox.length === 0) {
                                    fits = [];
                                    ciudadList.innerHTML = '';
                                }

                                outputHtml(fits);
                            };


                            // show results in HTML
                            const outputHtml = fits => {
                                if (fits.length > 0) {
                                    const html = fits
                                        .map(
                                            fit => `<option value="${fit.municipio}">${fit.municipio}</option>`
                                        )
                                        .join('');

                                    let ciudadList = document.getElementById('ciudad-list');

                                    ciudadList.innerHTML = html;


                                }
                            };


                            function cambiomedio(pantallaC) {



                                if (pantallaC.matches) { // If media query matches
                                    //para movil
                                    var ciudadesmovil = document.querySelectorAll('div.title-outer input[name="ciudad"]');

                                    ciudadesmovil.forEach(function (x) { x.setAttribute("id", "ciudadMovil") })
                                    document.getElementById('ciudadMovil').addEventListener('input', () => searchcity(ciudadMovil.value));

                                } else {
                                    //para escritorio

                                    var ciudadesdesktop = document.querySelectorAll('.title-container div.contact-more-info input[name="ciudad"]');

                                    ciudadesdesktop.forEach(function (x) { x.setAttribute("id", "ciudadDesktop") })
                                    document.getElementById('ciudadDesktop').addEventListener('input', () => searchcity(ciudadDesktop.value));

                                }
                            }

                            var pantallaC = window.matchMedia("(max-width: 800px)")
                            cambiomedio(pantallaC) // Call listener function at run time
                            pantallaC.addListener(cambiomedio) // Attach listener function on state changes

                    </script>

                    <?php }else{
                        
                        ?>
                        
                        <script defer> 
                        
                          
                            //Get cities From Json File
                            const searchcity = async searchBox => {
                                // ejecutar fetch...
                                const res = await fetch('<?php echo plugin_dir_url( __FILE__ );?>municipios.json');


                                const cities = await res.json();

                                //Get Entered Data
                                let fits = cities.filter(city => {
                                    const regex = new RegExp(`^${searchBox}`, 'gi');
                                    return city.municipio.match(regex);

                                });

                                if (searchBox.length === 0) {
                                    fits = [];
                                    ciudadList.innerHTML = '';
                                }

                                outputHtml(fits);
                            };


                            // show results in HTML
                            const outputHtml = fits => {
                                if (fits.length > 0) {
                                    const html = fits
                                        .map(
                                            fit => `<option value="${fit.municipio}">${fit.municipio}</option>`
                                        )
                                        .join('');

                                    let ciudadList = document.getElementById('ciudad-list');

                                    ciudadList.innerHTML = html;


                                }
                            };


                            function cambiomedio(pantallaC) {



                                if (pantallaC.matches) { // If media query matches
                                    //para movil
                                    var ciudadesmovil = document.querySelectorAll('input[name="ciudad"]');

                                    ciudadesmovil.forEach(function (x) { x.setAttribute("id", "ciudadMovil") })
                                    document.getElementById('ciudadMovil').addEventListener('input', () => searchcity(ciudadMovil.value));

                                } else {
                                    //para escritorio

                                    var ciudadesdesktop = document.querySelectorAll('input[name="ciudad"]');

                                    ciudadesdesktop.forEach(function (x) { x.setAttribute("id", "ciudadDesktop") })
                                    document.getElementById('ciudadDesktop').addEventListener('input', () => searchcity(ciudadDesktop.value));

                                }
                            }

                            var pantallaC = window.matchMedia("(max-width: 800px)")
                            cambiomedio(pantallaC) // Call listener function at run time
                            pantallaC.addListener(cambiomedio) // Attach listener function on state changes

                    </script>
                <?php 
                    }
        
            
        }


 //Funcion y accion para extraer la ip del cliente del usuario cre un shortcode que se inserta en el input de IP

 function get_the_user_ip() {
 if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
 //check ip from share internet
 $ip = $_SERVER['HTTP_CLIENT_IP'];
 } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
 //to check ip is pass from proxy
 $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
 } else {
 $ip = $_SERVER['REMOTE_ADDR'];
 }
 return apply_filters( 'wpb_get_ip', $ip );
 }

 add_shortcode('show_ip', 'get_the_user_ip');



 //	Funcion y accion para insertar shortcode custom para CF7 para la IP


 add_action('wpcf7_init', 'custom_add_form_tag_ip');

 function custom_add_form_tag_ip() {
     wpcf7_add_form_tag( 'ip', 'custom_ip_tag_handler', array( 'name-attr' => true ));
 }

 function custom_ip_tag_handler( $tag ) {
     $tag = '<input type="hidden" name="ip" value="' .do_shortcode("[show_ip]"). '" />';
    return $tag;
 }




 //	Funcion y accion para insertar shortcode custom para CF7 para Datalist de los municipios


 add_action( 'wpcf7_init', 'custom_add_form_tag_datalist' );

 function custom_add_form_tag_datalist() {
     wpcf7_add_form_tag( array( 'ciudad', 'ciudad*'),
         'wpcf7_ciudad_form_tag_handler', array( 'name-attr' => true ) );
 }

 function wpcf7_ciudad_form_tag_handler( $tag ) {
     $tag = new WPCF7_FormTag( $tag );

     if ( empty( $tag->name ) ) {
         return '';
     }

     $atts = array();

     $class = wpcf7_form_controls_class( $tag->type );
     $atts['class'] = $tag->get_class_option( $class );
     $atts['id'] = $tag->get_id_option();

     if ($tag->has_option('placeholder') || $tag->has_option('watermark')) {
        $atts['placeholder'] = $value;
        $value = '';
    }
     $atts['name'] = $tag->name;
     $atts = wpcf7_format_atts( $atts );


    if(is_amp_endpoint()){
        //para AMP
     $html = sprintf( '
            <amp-autocomplete filter="token-prefix" min-characters="0"
                src="/wp-content/plugins/cf7_UAO/amp-autocomplete-cities.json">
                <span class="wpcf7-form-control-wrap ciudad">
                <input type="text" name="ciudad" size="40" >
                </span>               
            </amp-autocomplete>
   ', $atts );
     return $html;}else{
         // para WP
        $html = sprintf( '<span class="wpcf7-form-control-wrap"><input type="text" list="ciudad-list" %s /> <datalist id="ciudad-list" ></datalist></span>', $atts );
        return $html;
     }

  
 }

   

 //	Funcion y accion para insertar shortcode custom para CF7 para la fecha y hora

 add_action('wpcf7_init', 'custom_add_form_date_time');

 date_default_timezone_set('America/Bogota');

 function custom_add_form_date_time() {
     wpcf7_add_form_tag( 'fecha', 'custom_date_time_tag_handler', array( 'name-attr' => true ));
 }

 function custom_date_time_tag_handler( $tag ) {
     $tag = '<input type="hidden" name="fecha" value="' . date("m/d/Y h:i:s A") . '" />';
    return $tag;
 }


 
//Para que el autocomplete funcione
    function ampautocomplete(){

      echo '<script async custom-element="amp-autocomplete" src="https://cdn.ampproject.org/v0/amp-autocomplete-0.1.js"></script>';

    }

    add_action('amp_post_template_head','ampautocomplete');

