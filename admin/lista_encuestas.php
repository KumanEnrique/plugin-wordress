<?php 
global $wpdb;
$tabla = "{$wpdb->prefix}encuestas";
$tabla2 = "{$wpdb->prefix}encuestas_detalle";
if(isset($_POST['btnGuardar'])){
    $nombreTxt = $_POST['nombre'];
    $query = "SELECT EncuestaId FROM {$wpdb->prefix}encuestas ORDER BY EncuestaId DESC LIMIT 1";
    $resultado = $wpdb->get_results($query,ARRAY_A);
    if($resultado[0]['EncuestaId'] == null){
        $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}encuestas");
        // echo "se trunco  la tabla $tabla ";
    }
    $proximoId = $resultado[0]['EncuestaId'] + 1;
    $shortcodeC = "[ENC id='$proximoId']";

    $datos = [
        "EncuestaId" => null,
        "Nombre" => $nombreTxt,
        "shortcode" => $shortcodeC,
    ];
    $respuesta = $wpdb->insert($tabla,$datos);
    if($respuesta){
        $lista_preguntas = $_POST['name'];
        $i = 0;
        foreach ($lista_preguntas as $key => $value) {
            $tipo = $_POST['type'][$i];
            $datos2 = [
                'DetalleId' => null,
                'EncuestaId' => $proximoId,
                'Pregunta' => $value,
                'Tipo' => $tipo,
            ];
            $wpdb->insert($tabla2,$datos2);
            $i++;
        }
    }
}


$query = "SELECT * FROM {$wpdb->prefix}encuestas";
$lista_encuestas = $wpdb->get_results($query,ARRAY_A);
if(empty($lista_encuestas)){
    $lista_encuestas = array();
}
?>
<div class="wrap">
    <?php echo "<h1>".get_admin_page_title()."</h1>"; ?>
    <br>
    <a class="page-title-action" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Añadir nueva</a>
    <br>
    <br>
    <br>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <th>Nombre de la encuesta</th>
            <th>Shortode</th>
            <th>Acciones</th>
        </thead>
        <tbody class="the-list">
            <?php 
            foreach ($lista_encuestas as $key => $value) {
                $id = $value['EncuestaId'];
                $nombre = $value['Nombre'];
                $shortcode = $value['ShortCode'];
                echo "
                <tr>
                    <td>$nombre</td>
                    <td>$shortcode</td>
                    <td> 
                        <a class='page-title-action'>Ver</a>
                        <a data-id='$id' class='page-title-action'>borrar</a>
                    </td>
                </tr>
                ";
            }
            
            ?>
        </tbody>
    </table>

<!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nueva encuesta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/pruebasMiasWP/dinosaurios/wp-admin/admin.php?page=serie_practica%2Fadmin%2Flista_encuestas.php" method="post">
                        <div class="mb-3">
                            <label for="nombre">Nombre de Encuesta</label>
                            <input type="text" name="nombre" id="nombre" autofocus="autofocus" class="form-control">
                        </div>
                        <h3 class="text-center my-3">PREGUNTAS</h3>

                        <div class="container-fluid" id="dinamicos">
                            <div class="row mb-1">
                                <div class="col-lg-3">
                                    <p>Pregunta 0</p>
                                </div>
                                <div class="col-lg-3">
                                    <input type="text" name="name[]" id="nombre" class="form-control name-list">
                                </div>
                                <div class="col-lg-3">
                                <select name="type0" id="type" class="form-control type-list">
                                    <option value="1" >SI O NO</option>
                                    <option value="2 selected">RANGO 0 - 5</option>
                                </select>
                                </div>
                                <div class="col-lg-3"><button name="add" id="add" class="btn btn-secondary">Agregar mas</button></div>
                            </div>
                        </div>

                        <!-- <table id="camposDinamicos">
                            <tr>
                                <td><label for="nombre">Preguntas</label></td>
                                <td><input type="text" name="name[]" id="nombre" class="form-control name-list"></td>
                                <td><select name="type0" id="type" class="form-control type-list">
                                    <option value="1" >SI O NO</option>
                                    <option value="2 selected">RANGO 0 - 5</option>
                                </select></td>
                                <td><button name="add" id="add" class="btn btn-secondary mt-4">Agregar mas</button></td>
                            </tr>
                        </table>
                        <table id="dinamicos"></table> -->
                        <button class="btn btn-primary" name="btnGuardar">Guardar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>
    </div>