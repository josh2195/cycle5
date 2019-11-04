<?php
/**
 * Created by PhpStorm
 * User: jbedw
 * Date: 11/4/2019
 * Time: 11:42 AM
 */
$pagetitle = "Create Account";
include_once "head.php";

//initiate variables
$showform = 1;
$errmsg = 0;
$errusername = "";
$erremail = "";
$errpassword = "";
$errpassword2 = "";
$errcaptcha = "";


if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //Sanitize user data
    $formdata['username'] = trim(strtolower($_POST['username']));
    $formdata['email'] = trim(strtolower($_POST['email']));
    $formdata['password'] = $_POST['password'];
    $formdata['password2'] = $_POST['password2'];


    //Check for empty fields
    if (empty($formdata['username'])) {$errusername = "Username is required."; $errmsg = 1; }
    if (empty($formdata['email'])) {$erremail = "Email is required."; $errmsg = 1; }
    if (empty($formdata['password'])) {$errpassword = "Password is required."; $errmsg = 1; }
    if (empty($formdata['password2'])) {$errpassword2 = "Confirmation password is required."; $errmsg = 1; }
    if (empty($_POST['g-recaptcha-response'])) {$errcaptcha = "ReCAPTCHA is required."; $errmsg = 1;}

    //Make sure the passowrd meets the requirements
    //Regular expression used to check for illegal characters in password
    $passreq = '/\R|\t|\0|\x0B/';
    $passlength = strlen($formdata['password']);
    //Checks against regular expression
    if (preg_match($passreq, $formdata['password']))
    {
        $errmsg = 1;
        $errpassword = "The password does not match requirements.";
    }
    //Checks password too short
    if ($passlength < 8){
        $errmsg = 1;
        $errpasslength = "The password is too short. Must be between 8-64 characters.";
    }
    //Checks password too long
    if ($passlength > 64) {
        $errmsg = 1;
        $errpasslength = "The password is too long. Must be between 8-64 characters.";
    }

    //Make sure password and confirmation password match
    if ($formdata['password'] != $formdata['password2'])
    {
        $errmsg = 1;
        $errpassword2 = "The passwords do not match.";
    }

    //Checks for duplicate username
    $sql = "SELECT * FROM helpdeskusers WHERE username = ?";
    $count = checkDup($pdo, $sql, $formdata['username']);
    if($count > 0)
    {
        $errmsg = 1;
        $errusername = "Username is taken. Please try a new one.";
    }

    //Checks for duplicate email
    $sql = "SELECT * FROM helpdeskusers WHERE email = ?";
    $count = checkDup($pdo, $sql, $formdata['email']);
    if($count > 0)
    {
        $errmsg = 1;
        $erremail = "The email is already in use.";
    }

    //Checks for valid email
    if(filter_var($formdata['email'], FILTER_VALIDATE_EMAIL)){
        //echo "Email is valid.";
    }
    else{
        $errmsg = 1;
        $erremail = "Email is invalid.";
    }

    //Error handling
    if($errmsg == 1)
    {
        echo "<p class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }
    else{

        //Hash the password
        $hashpassword = password_hash($formdata['password'], PASSWORD_BCRYPT);

        //Insert the user into the database
        try{
            $sql = "INSERT INTO helpdeskusers (username, email, password)
                    VALUES (:username, :email, :password) ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':username', $formdata['username']);
            $stmt->bindValue(':email', $formdata['email']);
            $stmt->bindValue(':password', $hashpassword);
            $stmt->execute();

            $showform =0;
            echo "<p class='awesome'>Thank you for creating an account!</p>";
        }
        catch (PDOException $e)
        {
            die( $e->getMessage() );
        }
    }
}

if($showform == 1)
{
    ?>
    <form name="createaccount" id="createaccount" method="post" action="createaccount.php">
        <table>
            <tr><th><label for="username">Username:</label><span class="important">*</span></th>
                <td><input name="username" id="username" type="text" size="20" placeholder="Username"
                           value="<?php if(isset($formdata['username'])){echo $formdata['username'];}?>"/>
                    <span class="error"><?php if(isset($errusername)){echo $errusername;}?></span></td>
            </tr>
            <tr><th><label for="email">Email:</label><span class="important">*</span></th>
                <td><input name="email" id="email" type="email" size="40" placeholder="Email"
                           value="<?php if(isset($formdata['email'])){echo $formdata['email'];}?>"/>
                    <span class="error"><?php if(isset($erremail)){echo $erremail;}?></span></td>
            </tr>
            <tr><th><label for="password">Password:</label><span class="important">*</span></th>
                <td><input name="password" id="password" type="password" size="40" placeholder="Password" />
                    <span class="error"><?php if(isset($errpassword)){echo $errpassword;}?></span></td>
            </tr>
            <tr><th><label for="password2">Password Confirmation:</label><span class="important">*</span></th>
                <td><input name="password2" id="password2" type="password" size="40" placeholder="Confirmation password" />
                    <span class="error"><?php if(isset($errpassword2)){echo $errpassword2;}?></span></td>
            </tr>
            <tr><th><label for="submit">Submit:</label></th>
                <td><span class="error"><?php if(isset($errcaptcha)){echo $errcaptcha;}?></span>
                    <div class="g-recaptcha" data-sitekey="6LevcB0UAAAAAI_Y_dKMg-bT_USxicPojFxWTgp_"></div>
                    <input type="submit" name="submit" id="submit" value="submit"/></td>
            </tr>

        </table>
    </form>
    <p class="important">* Indicates required field</p>
    <?php
}
include_once "foot.php";
?>