<!DOCTYPE html>
<html>
<body>

<h1>My first PHP page</h1>

<?php
	
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);

	echo "Hello World!";
?>

<?php

\r\n\r\ntry {\r\n   $conn = new PDO ( \"sqlsrv:server = tcp:rd4vxrj1mk.database.windows.net,1433; Database = School\", \"SQLAdmin\", \"{your_password_here}\");\r\n    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );\r\n}\r\ncatch ( PDOException $e ) {\r\n   print( \"Error connecting to SQL Server.\" );\r\n   die(print_r($e));\r\n}\r\n\rSQL Server Extension Sample Code:\r\n\r\n$connectionInfo = array(\"UID\" => \"SQLAdmin@rd4vxrj1mk\", \"pwd\" => \"{your_password_here}\", \"Database\" => \"School\", \"LoginTimeout\" => 30, \"Encrypt\" => 1);\r\n$serverName = \"tcp:rd4vxrj1mk.database.windows.net,1433\";\r\n$conn = sqlsrv_connect($serverName, $connectionInfo);

$server = "tcp:rd4vxrj1mk.database.windows.net:1433";
$user = "SQLAdmin";
$pwd = "Mhacks1234";
$db = "School";

$conn = sqlsrv_connect($server, array("UID"=>$user, "PWD"=>$pwd, "Database"=>$db));

if($conn === false){
	echo "Failure!";
} else {
	echo "Success!";
}

?>

</body>
</html>
