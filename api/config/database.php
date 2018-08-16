<?php

$serverName = "192.168.1.42";  

/* Connect using Windows Authentication. */  


global $conn;
global $conn_epicor;

try  
{  
  $conn = new PDO( "sqlsrv:server=$serverName ; Database=Calidad", "sa", "megamax");  
  $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

 // foreach($conn->query('SELECT * FROM Calidad.dbo.operations;') as $fila) {
//    print_r($fila);
//}
}  
catch(Exception $e)  
{   
  die( print_r( 'Error:'.$e->getMessage() ) );   
} 


$strConn="PROVIDER=MSDASQL;DRIVER={SQL Server};SERVER=192.168.1.42;DATABASE=Calidad;UID=sa;PWD=megamax;CharacterSet=UTF-8;";
$strConnEpicor="PROVIDER=MSDASQL;DRIVER={SQL Server};SERVER=192.168.1.42;DATABASE=Calidad;UID=sa;PWD=megamax;CharacterSet=UTF-8;";
