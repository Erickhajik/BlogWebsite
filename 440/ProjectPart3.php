<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Part 3 of Project</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <a href="Login.php"><button>Back To login Page</button></a>
    <h1>Comp 440 Project Part 3</h1>
</header>
<?php
$servername = "localhost";
$username = "comp440";
$password = "pass1234";
$database="comp440";
$makeConnection = new mysqli($servername,$username,$password,$database);


?>

<h2>Question 1</h2>
<p><b>List all the blogs of user X, such that all the comments are positive for these blogs.</b></p>
<h3>To View The Result Please Enter the buttom</h3>

<a href="Part3Question1.php"><button> Enter the Page Related to this question</button></a>

<h2>Question 2</h2>
<p><b>List the users who posted the most number of blogs on 10/10/2021; if there is a tie,list all the users who have a tie. </b></p>
<h3>Result</h3>




 <?php
$questoin2=$makeConnection->prepare("WITH test AS(SELECT created_by, COUNT(blogs.created_by) AS blogcount 
    FROM blogs WHERE blogs.pdate = '2021/11/26' 
    GROUP BY blogs.created_by),
maxNum AS(
    SELECT MAX(blogcount) AS maxBC 
    FROM test)
SELECT created_by
FROM test, maxNum
WHERE test.blogcount=maxNum.maxBC;");
$questoin2->execute();
$resultQ2 = $questoin2->get_result();
while ($row2 = $resultQ2->fetch_assoc()) {
echo $row2['created_by'];


}

$questoin2->close();
?> 








<h2>Question 3</h2>
<p><b>List the users who are followed by both X and Y. Usernames X and Y are inputs from the user.</b></p>
<h3>To View The Result Please Enter the buttom</h3>

<a href="Part3Question3.php"><button>Enter the Page Related to this question</button></a>


<h2>Question 4</h2>
<p><b>Display all the users who never posted a blog. </b></p>
<h3>Result</h3>

<?php
$questoin4=$makeConnection->prepare("SELECT username FROM users LEFT JOIN blogs on users.username=blogs.created_by WHERE blogs.blogid is null;");
$questoin4->execute();
$resultQ4 = $questoin4->get_result();
while ($row4 = $resultQ4->fetch_assoc()) {
    echo $row4['username'];
    echo " ";
}
$questoin4->close();
?>

<h2>Question 5</h2>
<p><b>Display all the users who posted some comments, but each of them is negative. </b></p>
<h3>Result</h3>

<?php
$questoin5=$makeConnection->prepare("SELECT posted_by FROM comments WHERE sentiment='negative' AND posted_by NOT IN (SELECT posted_by FROM comments WHERE sentiment='positive');");
$questoin5->execute();
$resultQ5 = $questoin5->get_result();
while ($row5 = $resultQ5->fetch_assoc()) {
    echo $row5['posted_by'];
    echo " ";
}
$questoin5->close();
?>

<h2>Question 6</h2>
<p><b>Display those users such that all the blogs they posted so far never received any negative comments.
    </b></p>
<h3>Result</h3>


<?php
$questoin6=$makeConnection->prepare("SELECT DISTINCT created_by
FROM comments
LEFT JOIN blogs
ON blogs.blogid=comments.blogid
WHERE comments.blogid NOT IN (SELECT comments.blogid
                        FROM comments
                        LEFT JOIN blogs
                        ON blogs.blogid=comments.blogid
                        WHERE comments.sentiment='negative');");
$questoin6->execute();
$resultQ6 = $questoin6->get_result();
while ($row6 = $resultQ6->fetch_assoc()) {
    echo $row6['created_by'];
    echo " ";
}
$questoin6->close();
$makeConnection->close();
?>













</body>
</html>