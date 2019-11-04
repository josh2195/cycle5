<?php
/**
 * Created by PhpStorm
 * User: jbedw
 * Date: 11/4/2019
 * Time: 11:54 AM
 */
$pagetitle = "Create New Ticket";
include_once "head.php";
checkLogin();

//initiate variables
$showform = 1;
$errmsg = 0;
$errusername = "";
$erremail = "";
$errcategory = "";
$errdescription = "";


if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //Sanitize user data
    $formdata['username'] = trim($_POST['username']);
    $formdata['email'] = trim($_POST['email']);
    $formdata['category'] = trim($_POST['category']);
    $formdata['description'] = trim($_POST['description']);

    //Check for empty fields
    if (empty($formdata['username'])) {$errusername = "Username is required."; $errmsg = 1; }
    if (empty($formdata['email'])) {$erremail = "Email is required."; $errmsg = 1; }
    if (empty($formdata['category'])) {$errcategory = "Please select a category."; $errmsg = 1; }
    if (empty($formdata['description'])) {$errdescription = "Please give a description."; $errmsg = 1; }

    //Error handling
    if($errmsg == 1)
    {
        echo "<p class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }
    else{

        try{
            $sql = "INSERT INTO helpdesktickets (username, email, category, description)
                    VALUES (:username, :email, :category, :description) ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':username', $formdata['username']);
            $stmt->bindValue(':email', $formdata['email']);
            $stmt->bindValue(':category', $formdata['category']);
            $stmt->bindValue(':description', $formdata['description']);
            $stmt->execute();

            $showform =0;
            echo "<p class='awesome'>Your ticket has been submitted. Thank you!</p>";
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
    <form name="ticket" id="ticket" method="post" action="helpdeskticket.php">
        <table>
            <tr>
                <th><label for="username">Username:</label><span class="error">*</span></th>
                <td><input name="username" id="username" type="text" size="20" placeholder="Username"
                    <span class="error"><?php if(isset($errusername)){echo $errusername;}?></span></td>
            </tr>
            <tr>
                <th><label for="email">Email:</label><span class="error">*</span></th>
                <td><input name="email" id="email" type="text" size="20" placeholder="example@example.com"
                    <span class="error"><?php if(isset($erremail)){echo $erremail;}?></span></td>
            </tr>
            <tr>
                <th><label for="category">Category:</label><span class="error">*</span></th>
                <td><select name="category" id="category">
                        <option id="category" value="Computer Support">Computer Support</option>
                        <option id="category" value="Moodle Help">Moodle Help</option>
                        <option id="category" value="Webadvisor Help">Webadvisor Help</option>
                        <option id="category" value="other">Other</option>
                    </select>
                    <span class="error"><?php if(isset($errcategory)){echo $errcategory;}?></span></td>
            </tr>
            <tr>
                <th><label for="description">Description</label></th>
                <td><span class="error"><?php if (isset($errdescription)) {echo $errdescription;} ?></span>
                    <textarea name="description" id="description"></textarea>
                </td>
            </tr>
        </table>
        <input type="submit" name="submit" id="submit" value="submit"/></td>
    </form>
    <p class="important">* Indicates required field</p>
    <?php
}
include_once "foot.php";
?>