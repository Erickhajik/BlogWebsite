<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
$UserName="";
$firstName="";
$LastName="";
$Email="";
$Password="";
$ConfirmPassword="";
$error="";
$check1="";
$check2="";
$num_rows1=0;
$num_rows2=0;
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $UserName = test_input($_POST["Username"]);
    $firstName = test_input($_POST["FirstName"]);
    $LastName = test_input($_POST["LastName"]);
    $Email = test_input($_POST["EmailAddress"]);
    $Password = test_input($_POST["Password"]);
    $ConfirmPassword = test_input($_POST["ConfirmPassword"]);
    if (empty($UserName)) {
        $error .= "Please Enter a Username<br>";
    } elseif (empty($firstName)) {
        $error .= "Please Enter a FirstName<br>";
    } elseif (empty($LastName)) {
        $error .= "Please Enter a LastName<br>";
    } elseif (empty($Email)) {
        $error .= "Please Enter an Email<br>";
    } elseif (empty($Password)) {
        $error .= "Please Enter a Password<br>";
    } elseif (empty($ConfirmPassword)) {
        $error .= "Please fill out the confirmPassword<br>";
    }
    elseif ($ConfirmPassword != $Password) 
    {
        $error .= "Confirm Password and Password dont match<br>";
    }
    if (!empty($error)) {
        echo("<p>" . $error . "</p>");
    }
    else
    {
    $checkUserName=$makeConnection->prepare("SELECT username FROM users WHERE username IN (?)");
    $checkEmail=$makeConnection->prepare("SELECT email FROM users WHERE email IN (?)");
    $checkUserName->bind_param("s", $UserName);
    $checkUserName->execute();
    $result = $checkUserName->get_result();
    $num_rows1 = $result->num_rows;
    $checkEmail->bind_param("s", $Email);
    $checkEmail->execute();
    $result1 = $checkEmail->get_result();
    $num_rows2 = $result1->num_rows;

    if ($num_rows1 > 0) 
    {
        $check1 .= "User with this Usename Already Exist<br>";
    } elseif ($num_rows2 > 0) 
    {
        $check1 .= "User with this Email Address Already Exist<br>";
    } 
    if (!empty($check1)) {
        echo("<p>" . $check1 . "</p>");
    }

    else 
    {
        $addinfo= $makeConnection->prepare("INSERT INTO users(username, password, firstName, lastName, email) VALUES (?,?,?,?,?)");
        $addinfo->bind_param("sssss", $UserName, $Password, $firstName, $LastName, $Email);
        $addinfo->execute();
        if ($num_rows1 == 0 && $num_rows2 == 0) 
        {
            header('Location:Login.php');
        }
         $addinfo->close();
    }

    $checkEmail->close();
    $checkUserName->close();
     
    $makeConnection->close();
}
  
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<h2>Sign Up</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>First Name:
        <input type="text" name="FirstName">
    </label>
    <br><br>
    <label>Last Name: <input type="text" name="LastName"></label>
    <br><br>
    <label> Email: <input type="text" name="EmailAddress"></label>
    <br><br>
    <label> User Name: <input type="text" name="Username"></label>
    <br><br>
    <label> Password: <input type="text" name="Password"></label>
    <br><br>
    <label>Confirm Password: <input type="text" name="ConfirmPassword"></label>
    <br><br>
    <button>Sign Up</button>
</form>
<a href="Login.php"><button>Back To Login</button></a>
</body>
</html>

