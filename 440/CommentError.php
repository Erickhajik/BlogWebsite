<?php
$erri=$_GET["erri"];
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <style>
        table, th, td {
            border:1px solid black;
            width: 100%;
        }
    </style>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
</header>
<?php
echo $erri;
?>
<br>
<a href="Show.php"><button>Show the blogs</button></a>
</body>
</html>