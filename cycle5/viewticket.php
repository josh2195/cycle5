<?php
/**
 * Created by PhpStorm
 * User: jbedw
 * Date: 11/4/2019
 * Time: 1:55 PM
 */
$pagetitle = "View Ticket";
include_once "head.php";
require_once "functions.php";
checkLogin();

$showform = 1;
$errmsg = 0;

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    // We are just echoing out the search term for the user's benefit
    echo "<p>Searching for:  " .  $_POST['id'] . "</p>";
    echo "<hr />";

    //Sanitize input
    $formdata['id'] = trim($_POST['id']);

    //Check empty fields
    if (empty($formdata['id'])){
        $errterm = "You must input your ticket ID.";
        $errmsg = 1;
    }

    //only go into the try/catch if you have no errors
    if($errmsg == 1)
    {
        echo "<p class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }
    else {
        try {
            //query the data
            $sql = "SELECT * 
                    FROM helpdesktickets 
                    WHERE ID
                    LIKE '{$formdata['id']}%'";
            //prepares a statement for execution
            $stmt = $pdo->prepare($sql);
            //executes a prepared statement
            $stmt->execute();
            //Returns an array containing all of the result set rows
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) {
                echo "<p class='error'>There are no results.  Please try a different search term.</p>";
            } else {
                //display the results for the user
                echo "<p class='success'>The following results matched your search:</p>";
                //loop through the results and display to the screen
                echo "<table><tr><th>Username</th><th>Email</th><th>Category</th><th>Description</th></tr>";
                foreach ($result as $row) {
                    echo "<tr><td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['category'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                $showform = 0;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
if($showform ==1) {
    ?>
    <form name="ticketsearch" id="ticketcearch" method="post" action="viewticket.php">
        <label for="id">Ticket ID:</label><span class="error">*</span>
        <input name="id" id="id" type="text" />
        <span class="error"><?php if(isset($errid)){echo $errid;}?></span>
        <br />
        <input type="submit" name="submit" id="submit" value="submit" />
    </form>
    <?php
}
require_once "foot.php";
?>