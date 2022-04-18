<?php 

class codigoCorto{
    public function obtenerEncuesta($encuestaId){
        global $wpdb;
        $tabla = "{$wpdb->prefix}encuestas";
        $query = "SELECT * FROM $tabla WHERE EncuestaId = '$encuestaId' ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos[0];
    }
    public function obtenerEncuestaDetalle($encuestaId){
        global $wpdb;
        $tabla2 = "{$wpdb->prefix}encuestas_detalle";
        $query = "SELECT * FROM $tabla2 WHERE EncuestaId = '$encuestaId' ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }
    public function formOpen( $titulo)
    {
        $html = "
            <div class='wrap'>
            <h3>$titulo</h3>
            <form method='post'>
        ";
        return $html;
    }
    public function formClose(){
        $html = "
        <input type='submit' id='btnGuardar' name='btnGuardar' class='wp-block-button__link' value='enviar'>
        </form>
        </div>
        ";
        return $html;
    }
    public function formInput($detalleId,$pregunta,$tipo){
        $html = "";
        if($tipo == 1){
            $html = "
            <div class='mb-3'>
                <p>$pregunta</p>
                <div class='col-sm-6'>
                    <select class='form-control' id='$detalleId' name='$detalleId'>
                        <option value='si'>Si</option>
                        <option value='no'>No</option>
                    </select>
                </div>
            </div>";
        }else{
            $html = "
            <div class='mb-3'>
                <p>$pregunta</p>
                <div class='col-sm-6'>
                    <select class='form-control' id='$detalleId' name='$detalleId'>
                        <option value='rango1-0'>rango 1</option>
                        <option value='no'>No</option>
                    </select>
                </div>
            </div>";
        }
        return $html;
    }

    function Armador($encuestaid){
        $enc = $this->obtenerEncuesta($encuestaid);
        $nombre = $enc['Nombre'];
        //obtener todas las preguntas
        $preguntas = "";
        $listapregutas = $this->obtenerEncuestaDetalle($encuestaid);
        // var_dump($listapregutas[0]);
        foreach ($listapregutas as $key => $value) {
            $detalleid = $value['DetalleId'];
            $pregunta = $value['Pregunta'];
            $tipo =$value['Tipo'];
            $encid = $value['EncuestaId'];

            if($encid == $encuestaid){
                $preguntas .= $this->formInput($detalleid,$pregunta,$tipo);
            }
        }
        $html = $this->formOpen($nombre);
        $html .= $preguntas;
        $html .= $this->formClose();
        return $html;
    }


    function GuardarDetalle($datos){
        global $wpdb;
        $tabla = "{$wpdb->prefix}encuestas_respuesta"; 
        return  $wpdb->insert($tabla,$datos);
    }

}
?>