<?php
/**
 * Created by PhpStorm
 * User: jbedw
 * Date: 11/4/2019
 * Time: 11:47 AM
 */

$pagetitle = "Sign In";
include_once "head.php";

//initiate variables
$showform = 1;
$errormsg = 0;
$errusername = "";
$errpassword = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //trim usernames and password to get rid of whitespace and make usernames lowercase
    $formdata['username'] = trim(strtolower($_POST['username']));
    $formdata['password'] = $_POST['password'];

    //check for empty fields
    if (empty($formdata['username'])) {
        $errusername = "The username is required.";
        $errormsg = 1;
    }
    if (empty($formdata['password'])) {
        $errpassword = "The password is required.";
        $errormsg = 1;
    }

    if($errormsg == 1)
    {
        echo "<p class='error'>Please fix errors.</p>";
    }
    else{
        //confirm correct username/password combination
        try
        {
            $sqlusers = "SELECT * FROM helpdeskusers WHERE username = :username";
            $stmtusers = $pdo->prepare($sqlusers);
            $stmtusers->bindValue(':username', $formdata['username']);
            $stmtusers->execute();
            $row = $stmtusers->fetch();
            $countusers = $stmtusers->rowCount();
            if ($countusers < 1)
            {
                echo  "<p class='important'>Cannot find this username.</p>";
            }
            else {
                if (password_verify($formdata['password'], $row['password'])) {
                    $_SESSION['ID'] = $row['ID'];
                    $_SESSION['username'] = $row['username'];
                    $showform = 0;
                    header("Location: confirmuser.php?state=2");
                } else {
                    echo "<p class='error'>The username and password combination you entered is not correct.  Please try again.</p>";
                }
            }
        }
        catch (PDOException $e)
        {
            echo "<div class='error'><p></p>ERROR selecting members!" .$e->getMessage() . "</p></div>";
            exit();
        }
    }
}
if($showform == 1){
    ?>
    <form name="signin" id="signin" method="POST" action="signin.php">

    <table>
        <tr><th><label for="username">Username:</label><span class="important">*</span></th>
            <td><input name="username" id="username" type="text" placeholder="Username"
                       value="<?php if(isset($formdata['username']))
                       {echo $formdata['username'];
                       }?>" /><span class="important"><?php if(isset($errorusername)){echo $errorusername;}?></span></td>
        </tr>
        <tr><th><label for="password">Password:</label><span class="important">*</span></th>
            <td><input name="password" id="password" type="password" placeholder="Password" /><span class="important"><?php if(isset($errorpassword)){echo $errorpassword;}?></span></td>
        </tr>
        <tr><th><label for="submit">Submit: </label></th>
            <td><input type="submit" name="submit" id="submit" value="submit"/></td>
        </tr>
    </table>
    <p class="important">* Indicates required field</p>
    <?php
}
require_once "foot.php";
?>