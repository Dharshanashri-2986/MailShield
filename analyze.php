<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Analyze Email | MailShield</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- =========================
            NAVIGATION BAR
    ========================== -->

    <header>

        <nav class="navbar">

            <div class="logo">

                <img src="images/logo.png" alt="MailShield Logo">

                <h2>MailShield</h2>

            </div>

            <ul>

                <li><a href="index.html">Home</a></li>

                <li><a href="analyze.php" class="active">Analyze</a></li>

                <li><a href="history.php">History</a></li>

            </ul>

        </nav>

    </header>


    <!-- =========================
            ANALYZE SECTION
    ========================== -->

    <section class="analyze-section">

        <div class="analyze-container">

            <h1>Analyze Suspicious Email</h1>

            <p>

                Paste the email content below or upload
                a screenshot. MailShield will analyze it
                and generate a phishing report.

            </p>

            <form action="process.php"
                  method="POST"
                  enctype="multipart/form-data">

                <!-- Email Text -->

                <label>

                    Paste Email Content

                </label>

                <textarea
                    name="email_text"
                    rows="12"
                    placeholder="Paste the complete email here..."></textarea>


                <div class="divider">

                    OR

                </div>


                <!-- Image Upload -->

                <label>

                    Upload Email Screenshot

                </label>

                <input
                    type="file"
                    name="email_image"
                    accept=".png,.jpg,.jpeg">


                <button
                    type="submit"
                    class="primary-btn">

                    Analyze Email

                </button>

            </form>

        </div>

    </section>


    <!-- =========================
                FOOTER
    ========================== -->

    <footer>

        <p>

            © 2026 MailShield

            <br>

            Intelligent Phishing Email Detection &
            Analysis System

        </p>

    </footer>

</body>

</html>