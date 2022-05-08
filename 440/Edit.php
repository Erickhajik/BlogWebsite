<?php
session_start();
$servername = "localhost";
$username = "comp440";
$password = "pass1234";
$database="comp440";
$makeConnection = new mysqli($servername,$username,$password,$database);
$id = $_GET["id"];    // id is ok
$createdby=$_GET['created'];
$Descr=$_GET["Descript"];
$Subject=$_GET["subject"];
$datep=$_GET["dateP"];
$date=date("Y/m/d");
$s1=$_SESSION['UserName'];
$err="";
$error="";
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit a Blog</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <a href="Show.php"><button>Back</button></a>
    <?php
    if($s1!= $createdby)
    {
        $error="Cannot Edit someone else post";
    }
    IF (!empty($error))
    {
        header("Location:CommentError.php?erri=" .$error);
    }
    ?>
</header>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $Description = test_input($_POST["Description"]);
    $Subject = test_input($_POST["Subject"]);
    $s=$_POST['blogId'];
    $date=date("Y/m/d");
    $creaftedby = test_input($_POST["createdby"]);
    $UpdateBlog =$makeConnection->prepare("UPDATE blogs set subject=?,description=? where blogid=?;");
    $UpdateBlog->bind_param("sss",$Subject,$Description,$s);
    $UpdateBlog->execute();
    $UpdateBlog->close();
    header('Location:Show.php');
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
<h2>Create a Blog</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Subject: <input type="text" name="Subject" value="<?php echo $Subject;?>"></label>
    <br><br>
    <label>Created By: <input type="text" name="createdby" value="<?php echo $createdby; ?>" readonly></label>
    <br><br>
    <label>Blog Id: <input type="text" name="blogId" value="<?php echo $id; ?>" readonly></label>
    <br><br>
    <label> Description: <textarea name="Description" rows="5" cols="40"><?php  echo $Descr;?></textarea></label>
    <br><br>
    <button>Submit</button>
</form>
</body>
</html>

