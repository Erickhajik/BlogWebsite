

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Part 3 Question 1</title>
     <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <h1>Comp 440 Project Question 1</h1>
</header>
<?php
$servername = "localhost";
$username = "comp440";
$password = "pass1234";
$database="comp440";
$makeConnection = new mysqli($servername,$username,$password,$database);
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $User = test_input($_POST["User"]);


 

$leadername1=$makeConnection->prepare("SELECT blogs.created_by,blogs.blogid,COUNT(blogs.blogid) FROM blogs WHERE blogs.created_by=? AND blogs.blogid IN(SELECT comments.blogid FROM comments WHERE comments.sentiment='negative')=0 GROUP BY blogid;");
  $leadername1->bind_param("s",$User);
        $leadername1->execute();
        $result = $leadername1->get_result();
          $num_rows = $result->num_rows;
        if($num_rows>0)
        {
    while ($row = $result->fetch_assoc()) {
    echo("<p><b> Blog id</b></p>");
     echo("<p>".$row['blogid']."</p>");
    echo("<p><b> Created by</b></p>");
         echo("<p>".$row['created_by']."</p>");
 
}
        }
    
    ELSE
    {
        echo "<p><b>this record has at least one or more negative comments</b></p>";
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
    <label>User: <input type="text" name="User"></label>
    <br><br>
    <button>Submit</button>
</form>
 <a href="ProjectPart3.php"><button>Back to Part3</button></a>



</body>
</html>
