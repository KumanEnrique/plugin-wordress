<?php
/*
Plugin Name: serie practica
Plugin URI: https://codex.wordpress.org/Plugin_API/Action_Reference
Description: plugin de ejemplo
Version: 0.0.1
Author:kuman hoy luis enrique
Author URI: https://lesterchan.net
Text Domain: kux-local-domain
*/
// add_action('wp_head','hablar');

// function hablar(){
//     print('
//     <script> console.log("hola en plugin 4")</script>
//     ');
// }

// requires
require_once  dirname(__FILE__).'./clases/codigoCorto.php';

function activar(){
    /* activar el plugin y crear las tablas
    */
    global $wpdb;
    $sql ="CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas(
        `EncuestaId` INT NOT NULL AUTO_INCREMENT,
            `Nombre` VARCHAR(45) NULL,
            `ShortCode` VARCHAR(45) NULL,
            PRIMARY KEY (`EncuestaId`));";

    $wpdb->query($sql);
    $sql2 ="CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas_detalle(
        `DetalleId` INT NOT NULL AUTO_INCREMENT,
            `EncustaId` INT NOT NULL,
            `Pregunta` VARCHAR(150) NULL,
            `Tipo` VARCHAR(45) NULL,
            PRIMARY KEY (`DetalleId`));";
    $wpdb->query($sql2);
    $sql3 ="CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas_respuesta(
        `RespuestaId` INT NOT NULL AUTO_INCREMENT,
            `DetalleId` INT NULL,
            `Respuesta` VARCHAR(45) NULL,
            PRIMARY KEY (`RespuestaId`));";
            //agregar campo Codigo VARCHAR NULL
    $wpdb->query($sql3);

}
function desactivar(){
    flush_rewrite_rules();
}
/* 
function desinstalar(){
    print('estas seguro de lo que vas hacer');
}
register_uninstall_hook(__FILE__,"desinstalar");
2             */
register_activation_hook(__FILE__,"activar");
register_deactivation_hook(__FILE__,"desactivar");
add_action('admin_menu','CrearMenu');
function CrearMenu(){
    add_menu_page(
        'menu personalizado',//titulo de la pagina
        'super encuestas menu',//titulo del menu
        'manage_options',//capability
        plugin_dir_path(__FILE__).'admin/lista_encuestas.php',//slug
        null,//callback
        plugin_dir_url(__FILE__).'admin/img/risa.jpg',
        '1'//priority
    );
    // add_submenu_page(
    //     'sp_menu',//parent slug
    //     'Ajustes',//titulo de la pagina
    //     'Austes',//titulo del menu
    //     'manage_options',//capability
    //     'sp_menu_ajustes',//slug
    //     'submenu'//callback
    // );
}
function MostrarContenido(){
    print('<h1>contenido del plugin</h1>');
}
// function submenu(){
//     print('<h1>contenido de la subpagina: </h1>');
// }

//encolar boostrap
function EncolarBootstrapJS($hook){
    // echo "<script>console.log('$hook')</script>";
    if($hook != 'serie_practica/admin/lista_encuestas.php'){
        return ;
    }
    wp_enqueue_script('bootstrapJs',plugins_url('admin/bootstrap/js/bootstrap.min.js',__FILE__));
    // wp_enqueue_script('bootstrapJs',plugins_url('admin/bootstrap/js/bootstrap.min.js',__FILE__),array('jquery') );
}
add_action('admin_enqueue_scripts','EncolarBootstrapJS');
//encolar boostrap
function EncolarBootstrapCSS($hook){
    if($hook != 'serie_practica/admin/lista_encuestas.php'){
        return ;
    }
    wp_enqueue_style('bootstrapCss',plugins_url('admin/bootstrap/css/bootstrap.min.css',__FILE__));
}
add_action('admin_enqueue_scripts','EncolarBootstrapCSS');
// encolar js propio
function EncolarJs($hook){
    if($hook != 'serie_practica/admin/lista_encuestas.php'){
        return ;
    }
    wp_enqueue_script('jsPropio',plugins_url('admin/js/listas_encuestas.js',__FILE__));
    wp_localize_script('jsPropio','SolicitudesAjax',[
        'url' => admin_url('admin-ajax.php'),
        'seguridad' => wp_create_nonce('seg')
    ]);
}
add_action('admin_enqueue_scripts','EncolarJs');

//ajax
function eliminarEncuesta(){
    $nonce = $_POST['nonce'];
    if(wp_verify_nonce($nonce,'seg')){
        die('no tiene permisos para ejecutar este ajax');
    }
    $id = $_POST['id'];
    global $wpdb;
    $tabla = "{$wpdb->prefix}encuestas";
    $tabla2 = "{$wpdb->prefix}encuestas_detalle";
    $wpdb->delete($tabla,array('EncuestaId' => $id));
    $wpdb->delete($tabla2,array('EncuestaId' => $id));
    return true;
}

add_action('wp_ajax_peticionEliminar','eliminarEncuesta');

// shortcode

function imprimirShorCode($atts){
    $_short = new codigoCorto;
    $id = $atts['id'];

        //Programar las acciones del boton
        if(isset($_POST['btnGuardar'])){
            // var_dump($_POST);
            $listadePreguntas = $_short->ObtenerEncuestaDetalle($id);
            $codigo = uniqid();
            foreach ($listadePreguntas as $key => $value) {
                $idpregunta = $value['DetalleId'];
                if(isset($_POST[$idpregunta])){
                    $valortxt = $_POST[$idpregunta];
                    $datos = [
                        'DetalleId' => $idpregunta,
                        'Codigo' => $codigo,
                        'Respuesta' => $valortxt
                    ];
                    $_short->GuardarDetalle($datos);
                }
            }
            return " Encuesta enviada exitosamente!!!!!";
        }

    $html = $_short->Armador($id);
    return $html;
}
add_shortcode("enc","imprimirShorCode");