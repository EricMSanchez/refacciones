<?php
require_once('../Controllers/inspectionsController.php');

$controller = new InspectionsController();
$_POST = json_decode(file_get_contents('php://input'), true);

if(isset($_POST) && !empty($_POST)) {
    
    $guias = $controller->getGuias();

    if(count(json_decode($guias))>0) {
        //echo $inspection;
        echo '{"success": true,"message":'.$guias.'}';
    }else{
        echo '{"success": false,"message": "No hay guias registrados."}';
    }

}else{
    echo '{"success": false,"message": "09 Error: No se pudieron obtener los guias."}';
}
//echo phpinfo();