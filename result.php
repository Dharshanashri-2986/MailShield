<?php
session_start();

if (!isset($_SESSION["risk_score"])) {
    header("Location: analyze.php");
    exit();
}

$riskScore = $_SESSION["risk_score"];
$riskLevel = $_SESSION["risk_level"];
$summary = $_SESSION["summary"];
$keywords = $_SESSION["keywords"];
$urls = $_SESSION["urls"];
$emails = $_SESSION["emails"];
$emailText = $_SESSION["email_text"];
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Analysis Result | MailShield</title>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>

<nav class="navbar">

<div class="logo">

<img src="images/logo.png">

<h2>MailShield</h2>

</div>

<ul>

<li><a href="index.html">Home</a></li>

<li><a href="analyze.php">Analyze</a></li>

<li><a href="history.php">History</a></li>

</ul>

</nav>

</header>

<section class="result-container">

<h1>Analysis Report</h1>

<div class="score-card">

<h2>Risk Score</h2>

<div class="score-circle">

<?php echo $riskScore; ?>%

</div>

<h3>

<?php echo $riskLevel; ?>

</h3>

</div>

<div class="result-box">

<h2>Summary</h2>

<p>

<?php echo $summary; ?>

</p>

</div>

<div class="result-box">

<h2>Suspicious Keywords</h2>

<p>

<?php

if(empty($keywords))

echo "None";

else{

$wordArray = explode(",", $keywords);

foreach($wordArray as $word){

echo "<span class='keyword'>".trim($word)."</span>";

}

}

?>

</p>

</div>

<div class="result-box">

<h2>Detected URLs</h2>

<p>

<?php

if(empty($urls))

echo "No URLs Found";

else

echo nl2br($urls);

?>

</p>

</div>

<div class="result-box">

<h2>Detected Email Addresses</h2>

<p>

<?php

if(empty($emails))

echo "No Email Address Found";

else

echo nl2br($emails);

?>

</p>

</div>

<div class="result-box">

<h2>Analyzed Email</h2>

<pre>

<?php echo htmlspecialchars($emailText); ?>

</pre>

</div>

<div class="button-group">

<a href="analyze.php" class="primary-btn">

Analyze Another Email

</a>

<a href="history.php" class="secondary-btn">

View History

</a>

</div>

</section>

<footer>

<p>

© 2026 MailShield

</p>

</footer>

</body>

</html>