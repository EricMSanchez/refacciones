<?php
session_start();
header("Access-Control-Allow-Origin: *");
header('Cache-Control: no-cache, must-revalidate');
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('America/Tijuana');
require_once('../config/database.php'); 
class RefaccionesController
{

    public function getRefaccionesByName($refaccion){
            
        global $conn;
        $res = '[{"success":true,"message":"No se encontraron resultados."}]';

        if( $conn === false ) {
            $res = json_encode(
                array("message" => "Error: No results found, connection problem.")
            );
        }
        else
        {
            $stmt = $conn->prepare('
            SELECT p.Company, 
                    p.PartNum, 
                        p.PartDescription, 
                        c.Description AS Clase, 
                        g.Description AS Grupo, 
                        ISNULL(i.WarehouseCode, N\'S/I\') AS Almacen, 
                        ISNULL(i.BinNum, N\'S/I\') AS Deposito, 
                        ISNULL(i.OnhandQty, 0) AS Inventario, 
                        PP.MinimumQty as Minimo, 
                        PP.MaximumQty as Maximo,
                        p.IUM, 
                    REPLACE(ISNULL(ref.XFileName, N\'../../assets/img/default.png\'),\'F:\',\'http://192.168.1.25\') AS Imagen
                    FROM   Epicor10Live.Ice.XFileRef AS ref RIGHT OUTER JOIN
                        Epicor10Live.Ice.XFileAttch AS Att ON ref.Company = Att.Company AND ref.XFileRefNum = Att.XFileRefNum RIGHT OUTER JOIN
                        Epicor10Live.erp.Part AS p LEFT OUTER JOIN
                        Epicor10Live.erp.PartPlant as PP ON p.Company = PP.Company AND p.PartNum = PP.PartNum LEFT OUTER JOIN
                        Epicor10Live.erp.ProdGrup AS g ON p.Company = g.Company AND p.ProdCode = g.ProdCode LEFT OUTER JOIN
                        Epicor10Live.erp.PartClass AS c ON p.Company = c.Company AND p.ClassID = c.ClassID ON Att.Key1 = p.PartNum AND Att.Company = p.Company LEFT OUTER JOIN
                        Epicor10Live.erp.PartBin AS i ON p.Company = i.Company AND p.PartNum = i.PartNum
                    WHERE  (p.TypeCode = \'P\') AND 
                    (p.Company = \'Megaplst\') AND 
                         (p.ProdCode = \'REF\') AND 
                         (p.PartDescription LIKE \'%\' + \''.$refaccion.'\' + \'%\') OR
                    (p.TypeCode = \'P\') AND 
                         (p.Company = \'Megaplst\') AND 
                         (p.ProdCode = \'REF\') AND 
                         (c.Description LIKE \'%\' +\''.$refaccion.'\'+ \'%\')
            ');
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $res = json_encode($result);
            //sqlsrv_free_stmt( $stmt);
        
        }

        return $res;
    }



}