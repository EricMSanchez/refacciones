<?php
require_once('../Controllers/inspectionsController.php');

$controller = new InspectionsController();
$_POST = json_decode(file_get_contents('php://input'), true);

if(isset($_POST) && !empty($_POST)) {
    
    $jobnum = $_POST['jobnum'];

    $OT = $controller->existeOT($jobnum);

    if(count(json_decode($OT))>0) {
        //echo $inspection;
        echo '{"success": true,"message":'.$OT.'}';
    }else{
        echo '{"success": false,"message": "No hay OT registrados."}';
    }

}else{
    echo '{"success": false,"message": "09 Error: No se pudieron obtener los guias."}';
}
//echo phpinfo();