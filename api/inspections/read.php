<?php
require_once('../Controllers/inspectionsController.php');

$controller = new InspectionsController();
$_POST = json_decode(file_get_contents('php://input'), true);

if(isset($_POST) && !empty($_POST)) {
    $ordernum = $_POST['ordernum'];
    $token = $_POST['token'];
    
    $inspection = $controller->getInspectionByOrderNum($ordernum);

    if(count(json_decode($inspection))>0) {
        //echo $inspection;
        echo '{"success": true,"message":'.$inspection.'}';
    }else{
        echo '{"success": false,"message": "No se encontraron resultados para la Orden '.$ordernum.'"}';
    }

}else{
    echo '{"success": false,"message": "02 Error: No se pudieron obtener las inspecciones."}';
}
//echo phpinfo();