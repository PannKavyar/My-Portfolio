<?php
include("Includes/functions.php");

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
    $category = trim(filter_input(INPUT_POST, "category", FILTER_SANITIZE_STRING));
    $title = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING));
    $format = trim(filter_input(INPUT_POST, "format", FILTER_SANITIZE_STRING));
    $genre = trim(filter_input(INPUT_POST, "genre", FILTER_SANITIZE_STRING));
    $year = trim(filter_input(INPUT_POST, "year", FILTER_SANITIZE_NUMBER_INT));
    $details = trim(filter_input(INPUT_POST, "details", FILTER_SANITIZE_SPECIAL_CHARS)); 

    if ($name == "" || $email =="" || $category == "" || $title == "") {
        $error_message = "Please fill in the required fields: Name, Email, Category and Title";
    }
    //Error Message Priority => !isset($error_message)
    if (!isset($error_message) && $_POST["address"] != "") {
        $error_message = "Bad form input";
    }
    
    //Checking Valid or Invalid email address with PHPMailer
    //Error Message Priority => !isset($error_message)
    if (!isset($error_message) && !PHPMailer::validateAddress($email)) {
        $error_message = "Invalid Email Address!";
    }

    if (!isset($error_message)) {
        $email_body = "";
        $email_body .= "Name " . $name . "\n";
        $email_body .= "Email " . $email . "\n";
        $email_body .= "\n\nSuggested Item\n\n";
        $email_body .= "Category " . $category . "\n";
        $email_body .= "Title " . $title . "\n";
        $email_body .= "Format " . $format . "\n";
        $email_body .= "Genre " . $genre . "\n";
        $email_body .= "Year " . $year . "\n";
        $email_body .= "Details " . $details . "\n";

        $mail = new PHPMailer;

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        // SMTP::DEBUG_OFF = off (for production use)
        // SMTP::DEBUG_CLIENT = client messages
        // SMTP::DEBUG_SERVER = client and server messages
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;

        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;

        //Set the encryption mechanism to use - STARTTLS or SMTPS
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = 'aungzinlatt007@gmail.com';

        //Password to use for SMTP authentication
        $mail->Password = 'qavagulthlgvfauo';

        //It's important not to use the submitter's address as the from address as it's forgery,
        //which will cause your messages to fail SPF checks.
        //Use an address in your own domain as the from address, put the submitter's address in a reply-to
        $mail->setFrom('aungzinlatt007@gmail.com', $name);
        $mail->addReplyTo($email, $name);
        $mail->addAddress('aungzinlatt007@gmail.com', 'Aung Zin Latt');
        $mail->Subject = 'Library Suggestion Form ' . $name;
        $mail->Body = $email_body;
        if ($mail->send()) {
            header("location:suggest.php?status=thanks");
            exit;
        }
        $error_message = 'Mailer Error: ' . $mail->ErrorInfo;  
    }
}

$pageTitle = "Suggest a Media Item";
$section = "suggest";

include('Includes/header.php');

 ?>

<div class="section page">
    <div class="wrapper">
        <h1>Suggest a Media Item</h1>
        <?php if (isset($_GET["status"]) && $_GET["status"] == "thanks") {
            echo "<p>Thanks for the email! I&rsquo;ll check out your suggestion shortly!</p>";
        } else {
            //Displaying Error Message with invalid email
            if (isset($error_message)) {
                echo '<p class="message">' . $error_message . '</p>';
            } else {
                echo '
                <p>If you think there is something I&rsquo;m missing, let me know! Complete the form to send me an email.</p>';
            }
        ?>
        <form method="post" action="suggest.php">
            <table>
                <tr>
                    <th><label for="name">Name (required)</label></th>
                    <td><input type="text" id="name" name="name" value="<?php if (isset($name)) echo $name; ?>"></td>
                </tr>
                <tr>
                    <th><label for="email">Email (required)</label></th>
                    <td><input type="text" id="email" name="email" value="<?php if (isset($email)) echo $email; ?>"></td>
                </tr>
                <tr>
                    <th><label for="category">Category (required)</label></th>
                    <td>
                        <select name="category" id="category">
                            <option value="">Select One</option>
                            <option value="books"<?php if (isset($name) && $category == "Books") echo " selected"; ?>>Books</option>
                            <option value="movies"<?php if (isset($name) && $category == "Movies") echo " selected"; ?>>Movies</option>
                            <option value="music"<?php if (isset($name) && $category == "Music") echo " selected"; ?>>Music</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="title">Title (required)</label></th>
                    <td><input type="title" id="title" name="title" value="<?php if (isset($title)) echo $title; ?>"></td>
                </tr>
                <tr>
                    <th><label for="format">Format</label></th>
                    <td>
                        <select name="format" id="format">
                            <option value="">Select One</option>
                            <optgroup label="Books">
                                <option value="Audio"<?php if (isset($format) && $format == "Audio") echo " selected"; ?>>Audio</option>
                                <option value="Ebook"<?php if (isset($format) && $format == "Ebook") echo " selected"; ?>>Ebook</option>
                                <option value="Hardback"<?php if (isset($format) && $format == "Hardback") echo " selected"; ?>>Hardback</option>
                                <option value="Paperback"<?php if (isset($format) && $format == "Paperback") echo " selected"; ?>>Paperback</option>
                            </optgroup>
                            <optgroup label="Movies">
            						<option value="Blu-ray"<?php if (isset($format) && $format == "Blu-ray") echo " selected"; ?>>Blu-ray</option>
            						<option value="DVD"<?php if (isset($format) && $format == "DVD") echo " selected"; ?>>DVD</option>
            						<option value="Streaming"<?php if (isset($format) && $format == "Streaming") echo " selected"; ?>>Streaming</option>
            						<option value="VHS"<?php if (isset($format) && $format == "VHS") echo " selected"; ?>>VHS</option>
            					</optgroup>
            					<optgroup label="Music">
            						<option value="Cassette"<?php if (isset($format) && $format == "Cassette") echo " selected"; ?>>Cassette</option>
            						<option value="CD"<?php if (isset($format) && $format == "CD") echo " selected"; ?>>CD</option>
            						<option value="MP3"<?php if (isset($format) && $format == "MP3") echo " selected"; ?>>MP3</option>
            						<option value="Vinyl"<?php if (isset($format) && $format == "Vinyl") echo " selected"; ?>>Vinyl</option>
            					</optgroup>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="title">Genre</label></th>
                    <td>
                        <select name="genre" id="genre">
                            <option value="">Select One</option>
                            <?php
                            $genre_array = genre_array();
                            foreach($genre_array as $category => $options) {
                                echo "<optgroup label=\"$category\">";
                                foreach ($options as $option) {
                                    echo "<option value=\"$option\"";
                                    if (isset($genre) && $genre == "$option") {
                                        echo " selected";
                                    }
                                    echo ">$option</option>";
                                }			
                                echo "</optgroup>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="year">Year</label></th>
                    <td><input type="year" name="year" id="year" value="<?php if (isset($year)) echo $year; ?>"></td>
                </tr>
                <tr>
                    <th><label for="name">Additional Details</label></th>
                    <td><textarea name="details" id="details" cols="30" rows="5"><?php if (isset($details)) echo htmlspecialchars($_POST['details']); ?></textarea></td>
                </tr>
                <tr style="display: none">
                    <th><label for="address">Email</label></th>
                    <td><input type="text" id="address" name="address">
                    <p>Please leave this field blank</p>
                    </td>
                </tr>
            </table>
            <input class="suggest-submit" type="submit" value="Send" />
        </form>
        <?php } ?>
    </div>
</div>

<?php include('Includes/footer.php'); ?>