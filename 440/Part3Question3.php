

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Part 3 Question 3</title>
     <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <h1>Comp 440 Project Question 3</h1>
</header>
<?php
$servername = "localhost";
$username = "comp440";
$password = "pass1234";
$database="comp440";
$makeConnection = new mysqli($servername,$username,$password,$database);
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $UserX = test_input($_POST["UserX"]);
    $UserY = test_input($_POST["UserY"]);

       // SELECT leadername FROM follows WHERE followername = 'bob' AND leadername IN (SELECT leadername FROM follows where followername = 'jdoe'


$leadername1=$makeConnection->prepare("SELECT leadername FROM follows WHERE followername =? AND leadername IN (SELECT leadername FROM follows where followername = ?);");
  $leadername1->bind_param("ss",$UserX,$UserY);
        $leadername1->execute();
        $result = $leadername1->get_result();
        while ($row = $result->fetch_assoc()) {
  
     echo("<p>".$row['leadername']."</p>");
 
}
    $leadername1->close();
        $makeConnection->close();
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>User X: <input type="text" name="UserX"></label>
    <br><br>
    <label>User Y: <input type="text" name="UserY"></label>
    <br><br>
    <button>Submit</button>
</form>
 <a href="ProjectPart3.php"><button>Back to Part3</button></a>
</body>
</html>
