<?php
/*=========================================================
    MailShield
    process.php

    Purpose:
    Receives form data from analyze.php,
    calls Python scripts,
    stores results in MySQL,
    redirects to result.php.
=========================================================*/

session_start();

require_once("db.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] != "POST")
{
    die("Invalid Request");
}

$emailText = "";
$imageName = "";
$inputType = "";

/*=========================================================
            GET USER INPUT
=========================================================*/

// User pasted email
if (!empty(trim($_POST["email_text"])))
{
    $emailText = trim($_POST["email_text"]);
    $inputType = "TEXT";
}

// User uploaded image
elseif (
    isset($_FILES["email_image"]) &&
    $_FILES["email_image"]["error"] == 0
)
{
    $inputType = "IMAGE";

    $uploadFolder = "uploads/";

    if (!is_dir($uploadFolder))
    {
        mkdir($uploadFolder);
    }

    $imageName = uniqid() . "_" . basename($_FILES["email_image"]["name"]);

    $imagePath = $uploadFolder . $imageName;

    if (!move_uploaded_file($_FILES["email_image"]["tmp_name"], $imagePath)) {
    die("Failed to upload image.");
}

    // Call OCR
    $ocrCommand = "python python/ocr.py " . escapeshellarg($imagePath);

    $emailText = shell_exec($ocrCommand);
}

// No input
else
{
    die("Please enter email text or upload an image.");
}


/*=========================================================
        CREATE TEMP TEXT FILE
=========================================================*/

$tempFile = "uploads/temp_email.txt";

file_put_contents($tempFile, $emailText);


/*=========================================================
        CALL PYTHON ANALYZER
=========================================================*/

$pythonCommand = "python python/analyze.py " . escapeshellarg($tempFile);

$jsonOutput = shell_exec($pythonCommand);


/*=========================================================
        DELETE TEMP FILE
=========================================================*/

if(file_exists($tempFile))
{
    unlink($tempFile);
}


/*=========================================================
        DECODE JSON
=========================================================*/

$result = json_decode($jsonOutput, true);

if(!$result)
{
    die("Python analysis failed.");
}


/*=========================================================
        STORE VALUES
=========================================================*/

$riskScore = $result["risk_score"];
$riskLevel = $result["risk_level"];

$suspiciousWords = implode(", ", $result["suspicious_words"]);

$detectedUrls = implode(", ", $result["detected_urls"]);

$detectedEmails = implode(", ", $result["detected_emails"]);

$summary = $result["analysis_summary"];


/*=========================================================
        INSERT INTO DATABASE
=========================================================*/

$sql = "INSERT INTO scan_history
(
input_type,
email_text,
image_name,
risk_score,
risk_level,
suspicious_words,
detected_urls,
detected_emails,
analysis_summary
)

VALUES
(
?,
?,
?,
?,
?,
?,
?,
?,
?
)";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

$stmt,

"sssisssss",

$inputType,
$emailText,
$imageName,
$riskScore,
$riskLevel,
$suspiciousWords,
$detectedUrls,
$detectedEmails,
$summary

);

mysqli_stmt_execute($stmt);


/*=========================================================
        SAVE DATA FOR RESULT PAGE
=========================================================*/

$_SESSION["risk_score"] = $riskScore;
$_SESSION["risk_level"] = $riskLevel;
$_SESSION["summary"] = $summary;
$_SESSION["keywords"] = $suspiciousWords;
$_SESSION["urls"] = $detectedUrls;
$_SESSION["emails"] = $detectedEmails;
$_SESSION["email_text"] = $emailText;


/*=========================================================
        REDIRECT
=========================================================*/

header("Location: result.php");

exit();

?>