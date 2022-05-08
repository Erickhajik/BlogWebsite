<?php
session_start();
$servername = "localhost";
$username = "comp440";
$password = "pass1234";
$database="comp440";
$makeConnection = new mysqli($servername,$username,$password,$database);
$user=$_SESSION['UserName'];
if ($_SERVER["REQUEST_METHOD"] == "POST")
{

     $User12 = test_input($_POST["username1"]);
  $followerS=$makeConnection->prepare("SELECT * from follows Where leadername=? AND followername=? ;");
$followerS->bind_param("ss", $User12,$user);
$followerS->execute();
$followerSRe=$followerS->get_result();

IF($User12==$user)
{
echo "You Can not follow yourself";
}
elseif (mysqli_num_rows($followerSRe)>0) {
   
            echo "Sorry, you already followed this person";

}
ELSE {
   

    $follower=$makeConnection->prepare("INSERT INTO follows(leadername, followername) VALUES (?,?)");
$follower->bind_param("ss", $User12,$user);
$follower->execute();
$follower->close();

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
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Follow</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
     <a href="Show.php"><button>Back to Blogs</button></a>
</header>

<?php
   echo "<h3> All available Users: </h3>";

$DisplayAll=$makeConnection->prepare("SELECT username FROM users;");
$DisplayAll->execute();
$DisplayRe = $DisplayAll->get_result();
while ($DisplayRow = $DisplayRe->fetch_assoc()) {
      echo $DisplayRow['username'];
      ?>
     <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="text" name="username1" value=<?php echo $DisplayRow['username']; ?> hidden>
      <button>follow</button>
</form>
        <?php
}
$DisplayAll->close();



  echo "<h3> Users Followed by user: </h3>";


$unfollower=$makeConnection->prepare("SELECT * FROM follows WHERE followername=?;");
$unfollower->bind_param("s", $user);
$unfollower->execute();
$unfollowerRe = $unfollower->get_result();
while ($unfollowerRow = $unfollowerRe->fetch_assoc()) {
   echo "<p>" .$unfollowerRow['leadername']. "</p>";


}
$unfollower->close();
$makeConnection->close();
?>





</body>
</html>