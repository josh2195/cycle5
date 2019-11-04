<?php
/**
 * Created by PhpStorm
 * User: jbedw
 * Date: 10/21/2019
 * Time: 2:05 PM
 */
?>
<ul>
    <?php
    echo ($currentfile == "index.php") ? "<li>Home</li>" : "<li><a href='index.php'>Home</a></li>";
    echo ($currentfile == "createaccount.php") ? "<li>Create New Account</li>" : "<li><a href='createaccount.php'>Create New Account</a></li>";
    if(isset($_SESSION['ID'])){echo "<li><a href='helpdeskticket.php'>Create New Ticket</a></li>";}
    if(isset($_SESSION['ID'])){echo "<li><a href='viewticket.php'>View Ticket</a></li>";}
    echo (isset($_SESSION['ID'])) ? "<li><a href='signout.php'>Sign Out</a></li>" : "<li><a href='signin.php'>Sign In</a></li>";
    if (isset($_SESSION['ID'])){echo "Welcome back, " . $_SESSION['username'] . ". ";}
    ?>
</ul>