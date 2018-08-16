<?php
session_start();
header("Access-Control-Allow-Origin: *");
header('Cache-Control: no-cache, must-revalidate');
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('America/Tijuana');
require_once('../config/database.php'); 
class InspectionsController
{
public function getInspections(){
    
    
    global $conn;
    $res = "nada";

    if( $conn === false ) {
        die( print_r( sqlsrv_errors(), true));
    }else{

    $sql = "SELECT [inspections_id]".
    ",[operations_id]".
    ",[suboperations_id]".
    ",[users_id]".
    ",[created_date]".
    ",[modified_date]".
    ",[estatus_id]".
    ",[comments]".
    ",[orden]".
    ",[celda]".
    ",[turno]".
    " FROM [Calidad].[dbo].[inspections]";

    $stmt = $conn->execute( $sql );
    
    if( $stmt === false) {
        die( print_r( sqlsrv_errors(), true) );
    }else
    {
        $res = "";
    }


    $result = $conn->Execute($sql); 
    $rows = array();
    while ($r = $result->fetchRow())
    {
         array_push($rows, $r);
    } 
    $res = json_encode($rows);
    
    sqlsrv_free_stmt( $stmt);

    }
    return $res;
}

public function addEvento(
     $archivo_temp = null
    ,$archivo_name = null
    ,$falla = null
    ,$dictamen = null
    ,$origen = null
    ,$causa = null
    ,$file_name = null
    ,$file_path = null
    ,$oper_inspections_id = null
    ,$operacion_tipo = null
){
    global $conn;
    $res =  '[]';

    if( $conn === false ) {
        $res = json_encode(
            array("message" => "Error en eventos.")
        );
    }
    else
    {
        $stmt = $conn->prepare('
        INSERT INTO [dbo].[eventos]
           ([falla]
           ,[dictamen]
           ,[origen]
           ,[causa]
           ,[file_name]
           ,[file_path]
           ,[oper_inspections_id]
           ,[operacion_tipo]
           ,[created_date]) OUTPUT Inserted.evento_id
     VALUES
           (\''.$falla.'\'
           ,\''.$dictamen.'\'
           ,\''.$origen.'\'
           ,\''.$causa.'\'
           ,\''.$operacion_tipo.'_'.$archivo_name.'\'
           ,\''.$file_path.'\'
           ,\''.$oper_inspections_id.'\'
           ,\''.$operacion_tipo.'\'
           ,\''.date("Y-m-d H:i:s").'\')
        ');
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = json_encode($result);
        //sqlsrv_free_stmt( $stmt);
        
        $resp = exec('copy C:\\inetpub\\wwwroot\Calidad\api\files_uploaded\\'.$archivo_temp.' "\\\\192.168.1.14\\Calidad\\operaciones\\"'.json_decode($res)[0]->evento_id.'_'.$operacion_tipo.'_'.$archivo_name.'');
        
        
        
        if(strpos($resp,'1 file(s) copied') > 0){
            //$res = 'Se ha subido la imagen correctamente.';
            exec('erase C:\\inetpub\\wwwroot\Calidad\api\files_uploaded\\'.$archivo_temp.'');
        }else{
           // $res = 'Ocurrio un problema al subir la imagen.';
        }
        
    }
    
    return $res;
}

public function getGuias(){        
        
    global $conn;
    $res = "Empty";

    if( $conn === false ) {
        $res = json_encode(
            array("message" => "No products found.")
        );
    }
    else
    {
        $stmt = $conn->prepare('SELECT * FROM Calidad.dbo.guias where activo=1;');
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = json_encode($result);
        //sqlsrv_free_stmt( $stmt);
    
    }
    
    return $res;
}

public function getFallas(){        
        
    global $conn;
    $res = "Empty";

    if( $conn === false ) {
        $res = json_encode(
            array("message" => "No products found.")
        );
    }
    else
    {
        $stmt = $conn->prepare('SELECT a.value,a.name FROM (SELECT Key2 value,Key4 name,ROW_NUMBER() OVER(PARTITION BY Key4 ORDER BY Key2) rn FROM Epicor10Live.Ice.UD27 WHERE Key4 <> \'\') a WHERE rn =1;');
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = json_encode($result);
        //sqlsrv_free_stmt( $stmt);
    
    }
    
    return $res;
    }

public function createInspection(
    $users_id,
    $ins_status,
    $jobnum,
    $guia_id
    ){
    
    global $conn;
    $res = json_encode( array("inspections_id" => "0"));
    if( $conn === false ) {
        $res = json_encode(
            array("message" => "No products found.")
        );
    }
    else
    {
        $stmt = $conn->prepare('
        INSERT INTO [Calidad].[dbo].[inspections]
       ([users_id]
       ,[created_date]
       ,[modified_date]
       ,[estatus_id]
       ,[celda]
       ,[turno]
       ,[jobnum]
       ,[guia_id]) OUTPUT Inserted.inspections_id
 VALUES
       ('.$users_id.'
       ,\''.date("Y-m-d H:i:s").'\'
       ,\''.date("Y-m-d H:i:s").'\'
       ,'.$ins_status.'
       ,\'1\'
       ,\''.$this->calculateTurno().'\'
       ,\''.$jobnum.'\'
       ,'.$guia_id.');
    ');
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = json_encode($result);
        //sqlsrv_free_stmt( $stmt);
    
    }

    
    return $res;
}

public function getInspectionByOrderNum($JobOrderNum){
        
    global $conn;
    $res = "Empty";

    if( $conn === false ) {
        $res = json_encode(
            array("message" => "No products found.")
        );
    }
    else
    {
        $stmt = $conn->prepare('select distinct o.description,o.OpCode,jo.JobNum,isnull(oi.status,0) status,o.operations_id,jo.OprSeq from Epicor10Live.Erp.JobOper jo 
        join Calidad.dbo.operations o on jo.OpCode IN (Select oc.Opcode from Calidad.dbo.opcodes oc where oc.operation_id = o.operations_id)
		left join Calidad.dbo.oper_inspections oi on oi.operations_id = o.operations_id and oi.jobnum = jo.JobNum
        where jo.JobNum = \''.$JobOrderNum.'\' order by jo.OprSeq;');
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = json_encode($result);
        //sqlsrv_free_stmt( $stmt);
    
    }

    return $res;
}

public function existeOT($jobnum){
    //select distinct jo.JobNum from Epicor10Live.Erp.JobOper jo where jo.JobNum = '037038';
    global $conn;
    $res = "Empty";

    if( $conn === false ) {
        $res = json_encode(
            array("message" => "No products found.")
        );
    }
    else
    {
        $stmt = $conn->prepare('select distinct jo.JobNum from Epicor10Live.Erp.JobOper jo where jo.JobNum = \''.$jobnum.'\';');
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = json_encode($result);
        //sqlsrv_free_stmt( $stmt);
    
    }

    return $res;
}


public function calculateTurno(){
        
    if(date("H:i:s") >= '06:00' && date("H:i:s") <= '14:00')
    {
        return 'Primero';
    }
    else if(date("H:i:s") >= '14:00' && date("H:i:s") <= '22:00')
    {
        return 'Segundo';
    }
    else
    {
        return 'Tercero';
    }


}

}