<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Initialize Database</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
$servername = "localhost";
$username = "comp440";
$password = "pass1234";
$database = "comp440";
$makeConnection = new mysqli($servername,$username,$password,$database);
$filename = 'ProjDB.sql';
$op_data = '';
$lines = file($filename);
foreach ($lines as $line)
{
    if (substr($line, 0, 2) == '--' || $line == '')
    {
        continue;
    }
    $op_data .= $line;
    if (substr(trim($line), -1, 1) == ';')
    {
        $makeConnection->query($op_data);
        $op_data = '';
    }
}
echo "<h2>Table Created Inside</h2>". $database;
?>
<br>
<a href="Login.php"><button>Back to Login</button></a>
</body>
</html>