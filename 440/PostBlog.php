<?php
session_start();
$servername = "localhost";
$username = "comp440";
$password = "pass1234";
$database="comp440";
$makeConnection = new mysqli($servername,$username,$password,$database);
$err="";
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post a New Blog</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <a href="Show.php"><button>Back</button></a>
</header>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Description = test_input($_POST["Description"]);
    $Subject = test_input($_POST["Subject"]);
    $tags = test_input($_POST["Tags"]);
    $date=date("Y/m/d");
    $creaftedby = test_input($_POST["createdby"]);
    IF (empty($Description)) { $err .= "Description is empty<br>"; }
    elseif (empty($Subject)) {
        $err .= "Subject is empty<br>";
    }
    elseif (empty($tags)) {
        $err .= "tags is empty<br>";
    }
    IF (!empty($err)) {
        echo("<p>".$err."</p>");
    }
    ELSE
    {
        $checkPostsQuery="SELECT * from blogs WHERE pdate=? AND created_by=?";
        $checkPosts=$makeConnection->prepare($checkPostsQuery);
        $checkPosts->bind_param("ss", $date, $creaftedby);
        $checkPosts->execute();
        $res=$checkPosts->get_result();
        if (mysqli_num_rows($res)==2)
        {
            echo "Sorry, you can only post a blog twice a day. ";
        }
        else
        {
            $addBlog =$makeConnection->prepare("INSERT INTO blogs(subject,description,pdate,created_by) VALUES (?,?,?,?);");
            $addBlog->bind_param("ssss",$Subject,$Description, $date,$creaftedby);
            $addBlog->execute();
            $addBlog->close();
            $getblog= $makeConnection->prepare("SELECT blogid from blogs where subject = ? AND description=? AND pdate=? AND created_by=?;");
            $getblog->bind_param("ssss",$Subject,$Description, $date,$creaftedby);
            $getblog->execute();
            $testRes = $getblog->get_result();
            $testRow = mysqli_fetch_row($testRes);
            $getblog->close();
            $GettagId=$testRow[0];
            $string=$tags;
            $tags_array=explode(",",$string);
            foreach($tags_array as $hold)
            {
                $insertTag =$makeConnection->prepare("INSERT INTO blogstags(blogid,tag) VALUES (?,?);");
                $insertTag->bind_param("ss",$GettagId,$hold);
                $insertTag->execute();
                $insertTag->close();
            }
            header('Location:Show.php');
            $addBlog->close();
            $makeConnection->close();
        }
    }
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<h2>Create a Blog</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Subject: <input type="text" name="Subject"></label>
    <br><br>
    <label>Tags: <input type="text" name="Tags"></label>
    <br><br>
    <label> Created By: <input type="text" name="createdby" value="<?php echo $_SESSION['UserName']; ?>" readonly></label>
    <br><br>
    <label> Description: <textarea name="Description" rows="5" cols="40"></textarea></label>
    <br><br>
    <button>Submit</button>
</form>
</body>
</html>

