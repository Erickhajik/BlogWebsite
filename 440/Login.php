<?php
session_start();
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
     <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
</header>
<?php
$servername = "localhost";
$username = "comp440";
$password = "pass1234";
$database="comp440";
$makeConnection = new mysqli($servername,$username,$password,$database);
$error="";
$suc="";
$Usename="";
$Pass="";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $Usename = test_input($_POST["Username"]);
    $Pass = test_input($_POST["Password"]);
    IF (empty($Usename)) { $error .= "Please Enter the Username<br>"; }
    elseif (empty($Pass)) { $error .= "Please Enter the Password<br>";}
    IF (!empty($error)) { echo("<p>".$error."</p>"); }
    ElSE
    {
        $checkLogin=$makeConnection->prepare("SELECT username,password FROM users WHERE username=? AND password=? LIMIT 1");
        $checkLogin->bind_param("ss",$Usename,$Pass);
        $checkLogin->execute();
        $result = $checkLogin->get_result();
        $num_rows = $result->num_rows;
        if( $num_rows>0)
        {
            $_SESSION['UserName']=$Usename;
            header('Location:Show.php');
        }
        else
        {
             $suc .= "Invalid username or password<br>";
        }
        IF (!empty($suc))
        {
            echo("<p>".$suc."</p>");
        }
    }
    $checkLogin->close();
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
<h2>Login Page</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Username: <input type="text" name="Username"></label>
    <br><br>
    <label>Password: <input type="text" name="Password"></label>
    <br><br>
    <br><br>
    <button>Login</button>
</form>
<a href="SignUp.php"><button>SignUp</button></a>
<br>
<a href="InitializeDatabase.php"><button>Initilize database</button></a>
<br>
<a href="ProjectPart3.php"><button>Project Part 3 Stuff</button></a>
</body>
</html>


