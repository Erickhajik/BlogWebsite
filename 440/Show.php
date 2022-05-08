<?php
session_start();
$servername = "localhost";
$username = "comp440";
$password = "pass1234";
$database="comp440";
$makeConnection = new mysqli($servername,$username,$password,$database);
$name="";
$Display = $makeConnection->prepare("SELECT * FROM blogs");
$Display->execute();
$result = $Display->get_result();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        table, th, td {
            border:1px solid black;
            width: 100%;
        }
    </style>
    <meta charset="UTF-8">
    <title>View the Blogs</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <header>
        <h1>All avaliable Blogs</h1>
        <a href="PostBlog.php" style="float: left"><button> Create New Blog</button></a>
        <?php
        echo "Logged in as " .$_SESSION['UserName'];
        ?>
        <a href="follow.php" style="float: right"><button> Follow People</button></a>
        <a href="Login.php" style="float: right"><button> Log out</button></a>
    </header>
    <table>
        <tr>
            <th>description</th>
            <th>Subject</th>
            <th>Id</th>
            <th>date Posted</th>
            <th>Created by</th>
            <th>tags</th>
            <th>View the blog</th>
            <th>Delete the blog</th>
            <th>Edit the blog</th>
        </tr>
    <?php
    while ($row = $result->fetch_assoc()) {
        if($row["blogid"] != "" and $row["description"] !="" and $row["subject"] != "" and $row["pdate"] != "" and $row["created_by"] != "")
        {
        ?>
            <tr>
                <td><?php echo "" . $row["description"];?></td>
                <td><?php echo "" . $row["subject"];?></td>
                <td><?php echo "" . $row["blogid"];?></td>
                <td><?php echo "" . $row["pdate"];?></td>
                <td><?php echo "" . $row["created_by"];?></td>
                <?php
                $_SESSION['BLOGid']=$row["blogid"];
                $DisplayTags = $makeConnection->prepare("SELECT * FROM blogstags where blogid=?");
                $DisplayTags->bind_param("s", $_SESSION['BLOGid']);
                $DisplayTags->execute();
                $result1 = $DisplayTags->get_result();
while ($row1 = $result1->fetch_assoc()) {
    $name= $row1["tag"];
}
                ?>
                <td><?php echo $name;?></td>
                <td> <a href="Open.php?id=<?php echo $row['blogid']; ?>&created=<?php echo $row['created_by'];?>&dateP=<?php echo $row['pdate'];?>&Descript=<?php echo $row['description'];?>&subject=<?php echo $row['subject']; ?>" style="float: right"><button>Open</button></a>
                </td>
                <td> <a href="Delete.php?id=<?php echo $row['blogid']; ?>&created=<?php echo $row['created_by'];?>&dateP=<?php echo $row['pdate'];?>&Descript=<?php echo $row['description'];?>&subject=<?php echo $row['subject']; ?>" style="float: right"><button>Delete</button></a>
                </td>
                <td> <a href="Edit.php?id=<?php echo $row['blogid']; ?>&created=<?php echo $row['created_by'];?>&dateP=<?php echo $row['pdate'];?>&Descript=<?php echo $row['description'];?>&subject=<?php echo $row['subject']; ?>" style="float: right"><button>Edit</button></a>
                </td>
            </tr>
      <?php
    }
}
$DisplayTags->close();
    $makeConnection->close();
    $Display->close();
    ?>
    </table>
</body>
</html>
