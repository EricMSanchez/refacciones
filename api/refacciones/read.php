<?php
require_once('../Controllers/refaccionesController.php');

$controller = new RefaccionesController();
$_POST = json_decode(file_get_contents('php://input'), true);

if(isset($_POST) && !empty($_POST)) {
    $refac = $_POST['refaccion'];
    
    $reafacciones = $controller->getRefaccionesByName($refac);
    
    if(count(json_decode($reafacciones))>0) {
        //echo $inspection;
        echo '{"success": true,"message":'.$reafacciones.'}';
    }else{
        echo '{"success": false,"message": "No se han encontrado resultados para '.$refac.'"}';
    }

}else{
    echo '{"success": false,"message": "01 Error: No se pudieron obtener resultados debido a un problema con la consulta."}';
}
//echo phpinfo();