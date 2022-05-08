<?php
session_start();
$servername = "localhost";
$username = "comp440";
$password = "pass1234";
$database="comp440";
$makeConnection = new mysqli($servername,$username,$password,$database);
$err="";
$id = $_GET["id"];    // id is ok
$createdby=$_GET['created'];
$Descr=$_GET["Descript"];
$Subject=$_GET["subject"];
$datep=$_GET["dateP"];
$date=date("Y/m/d");
$s1=$_SESSION['UserName'];
$erri="";
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <style>
        table, th, td {
            border:1px solid black;
            width: 100%;
        }
    </style>
    <meta charset="UTF-8">
    <title>Delete All related things to a Blog</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <a href="Show.php"><button>Back to Blogs</button></a>
    <br><br>
    <?php
    if($s1!= $createdby)
    {
    $erri="Cannot Delete someone else post";
    }
    IF (!empty($erri))
    {
    header("Location:CommentError.php?erri=" .$erri);
    }
    ?>
</header>
<?php
echo "<table>";
echo "<tr>";
echo "<th>Description </th>";
echo "<th>Subject </th>";
echo "<th>Create by </th>";
echo "<th>Date posted </th>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo $Descr;
echo "</td>";
echo "<td>";
echo $Subject;
echo "</td>";
echo "<td>";
echo $createdby;
echo "</td>";
echo "<td>";
echo $datep;
echo "</td>";
echo "</tr>";
echo "</table>";
echo "<br>";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $s=$_POST['text'];
    $Deletecomment=$makeConnection->prepare("DELETE from comments WHERE blogid=?");
    $Deletecomment->bind_param("s",$s);
    $Deletecomment->execute();
    $Deletecomment->close();
    $DeleteTags=$makeConnection->prepare("DELETE from blogstags WHERE blogid=?");
    $DeleteTags->bind_param("s",$s);
    $DeleteTags->execute();
    $DeleteTags->close();
    $DeleteBlog =$makeConnection->prepare("DELETE from blogs WHERE blogid=?");
    $DeleteBlog->bind_param("s",$s);
    $DeleteBlog->execute();
    $DeleteBlog->close();
    $makeConnection->close();
    header("Location:Show.php");
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>
        <input name="text" value="<?php echo $id?>">
    </label>
    <br><br>
    <p>Are you sure you want to delete this record?</p>
    <button>Yes</button>
</form>
<a href="Show.php"><button>No</button></a>
</body>
</html>