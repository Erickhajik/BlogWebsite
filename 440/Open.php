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
$createdby1 =$_SESSION['UserName'];
$erri="";
$errico="";
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
    <title>Comment a Blog</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <a href="Show.php"><button>Show the Blogs </button></a>
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
echo "<p>Comments Related this post</p>";
$test="SELECT sentiment,description,cdate,posted_by FROM comments where blogid=?";
$showComment =$makeConnection->prepare($test);
$showComment->bind_param("i",$id);
$showComment->execute();
$result = $showComment->get_result();
$numRow = $result->num_rows;
while ($row = $result->fetch_assoc())
{
    echo "<table>";
    echo "<tr>";
    echo "<th>Description </th>";
    echo "<th>Subject </th>";
    echo "<th>Create by </th>";
    echo "<th>Date posted </th>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>";
    echo $row["description"];
    echo "</td>";
    echo "<td>";
    echo  $row["sentiment"];
    echo "</td>";
    echo "<td>";
    echo $row["posted_by"];
    echo "</td>";
    echo "<td>";
    echo $row["cdate"];
    echo "</td>";
    echo "</tr>";
    echo "</table>";
}
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $Description = test_input($_POST["Description"]);
    $tag=$_POST["get"];
    $s=$_POST['text'];
    $s24=$_POST['t1'];
if(empty($Description))
{
    $errico="Please write a description for the comment";
}
if(!empty($errico))
{
    header("Location:CommentError.php?erri=" .$errico);
}
else
{
    $q1="SELECT * from comments WHERE cdate=? AND posted_by=?";
    $checkQ1=$makeConnection->prepare($q1);
    $checkQ1->bind_param("ss", $date, $createdby1);                                                                      
    $checkQ1->execute();
    $res1=$checkQ1->get_result();
    $checkQ1->close();
    $q2="SELECT * from comments WHERE posted_by=? AND blogid=?";
    $checkQ2=$makeConnection->prepare($q2);
    $checkQ2->bind_param("ss", $createdby1,$s);                                                                      
    $checkQ2->execute();
    $res2=$checkQ2->get_result();
    $checkQ2->close();
    if($s24==$createdby1)
    {
        $erri .="Sorry, you cannot comment on your own blog post!";
    }
    else if (mysqli_num_rows($res1)==3)
    {
        $erri .="Sorry, you can only comment on blog posts 3 times a day.<br>";
    }
    else if(mysqli_num_rows($res2)==1)
    {
       $erri .="Sorry, you can only comment on a blog post exactly ONCE.";
    }
    IF (!empty($erri))
    {
        header("Location:CommentError.php?erri=" .$erri);
    }
    ELSE
    {
        $addcomment =$makeConnection->prepare("INSERT INTO comments(description,cdate,sentiment,posted_by,blogid) VALUES (?,?,?,?,?)");
        $addcomment->bind_param("sssss",$Description,$date,$tag,$createdby1,$s);
        $addcomment->execute();
        header('Location:Show.php');
    }
}
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
    <label>Description <textarea name="Description" rows="5" cols="40"></textarea></label>
    <input type="hidden" name="text" value="<?php echo $id?>">
    <br><br>
    <input type="hidden" name="t1" value="<?php echo $createdby?>">
    <br><br>
    <label>
        <select name="get">
            <option value = "positive"> positive
            </option>
            <option value = "negative"> negative
            </option>
        </select>
    </label>
    <br>
    <button>Submit</button>
</form>
</body>
</html>