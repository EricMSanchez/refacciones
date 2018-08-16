<?php
require_once('../Controllers/inspectionsController.php');
$controller = new InspectionsController();
$_POST = json_decode(file_get_contents('php://input'), true);

if(isset($_POST) && !empty($_POST)) {

    $ins_status = $_POST['ins_status'];
    $jobnum = $_POST['jobnum'];
    $users_id = $_POST['users_id'];
    $guia_id = $_POST['guia_id'];

    $subresp = $controller->createInspection($users_id,$ins_status,$jobnum,$guia_id);

    if(count(json_decode($subresp))>0) {
        //echo $inspection;
        echo '{"success": true,"message":'.$subresp.'}';
    }else{
        echo '{"success": false,"message": "07 Error: No se encontro registro."}';
    }

}else{
    echo '{"success": false,"message": "06 Error: Ocurrio un problema al intentar guardar la informacion"}';
}
