<?php
session_start();
require_once("db.php");

$records_per_page = 5;

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

if($page < 1)
{
    $page = 1;
}

$offset = ($page - 1) * $records_per_page;

$count_sql = "SELECT COUNT(*) AS total FROM scan_history";
$count_result = mysqli_query($conn, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);

$total_records = $count_row["total"];

$total_pages = ceil($total_records / $records_per_page);

$sql = "SELECT * FROM scan_history
        ORDER BY scan_time DESC
        LIMIT $records_per_page OFFSET $offset";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Scan History | MailShield</title>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>

<nav class="navbar">

<div class="logo">

<img src="images/logo.png" alt="Logo">

<h2>MailShield</h2>

</div>

<ul>

<li><a href="index.html">Home</a></li>

<li><a href="analyze.php">Analyze</a></li>

<li><a href="history.php" class="active">History</a></li>
</ul>

</nav>

</header>

<section class="history-container">

<h1>🛡 Scan History</h1>

<p class="history-count">

Showing <?php echo mysqli_num_rows($result); ?>

of

<?php echo $total_records; ?>

Scans

</p>

<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

$risk = strtolower(trim($row["risk_level"]));

if(strpos($risk, "high") !== false)
{
    $badge = "badge-high";
}
else if(strpos($risk, "medium") !== false)
{
    $badge = "badge-medium";
}
else
{
    $badge = "badge-low";
}

?>

<div class="history-card">

<div class="card-header">

<div>

<span class="<?php echo $badge; ?>">

<?php echo strtoupper($row["risk_level"]); ?>

</span>

</div>

<div class="scan-date">

<?php echo $row["scan_time"]; ?>

</div>

</div>

<div class="card-body">

<p>

<strong>Input Type :</strong>

<?php echo $row["input_type"]; ?>

</p>

<p>

<strong>Risk Score :</strong>

<?php echo $row["risk_score"]; ?>%

</p>

<p>

<strong>Email Preview :</strong>

<?php

$preview = substr($row["email_text"], 0, 80);

echo htmlspecialchars($preview);

if(strlen($row["email_text"]) > 80)
{
    echo "...";
}

?>

</p>

<button

class="view-btn"

onclick="toggleAnalysis('report<?php echo $row['scan_id']; ?>')">

View Full Analysis

</button>

<div

class="analysis-box"

id="report<?php echo $row['scan_id']; ?>">

<h3>Summary</h3>

<p>

<?php echo nl2br($row["analysis_summary"]); ?>

</p>

<h3>Suspicious Keywords</h3>

<p>

<?php echo $row["suspicious_words"]; ?>

</p>

<h3>Detected URLs</h3>

<p>

<?php echo $row["detected_urls"]; ?>

</p>

<h3>Original Email</h3>

<div class="email-box">

<?php echo nl2br(htmlspecialchars($row["email_text"])); ?>

</div>

</div>

</div>

</div>

<?php

}

}

else

{

?>

<div class="no-history">

No Scan History Found.

</div>

<?php

}

?>

<div class="pagination">

<?php

if($page>1)
{

?>

<a href="history.php?page=<?php echo $page-1; ?>">

← Previous

</a>

<?php

}

?>

<span>

Page

<?php echo $page; ?>

of

<?php echo $total_pages; ?>

</span>

<?php

if($page<$total_pages)
{

?>

<a href="history.php?page=<?php echo $page+1; ?>">

Next →

</a>

<?php

}

?>

</div>

</section>

<footer>

<p>

© 2026 MailShield

</p>

</footer>

<script>

function toggleAnalysis(id)
{

var x=document.getElementById(id);

if(x.style.display==="block")
{
x.style.display="none";
}

else
{
x.style.display="block";
}

}

</script>

</body>

</html>