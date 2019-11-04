<?php
/**
 * Created by PhpStorm.
 * User: joshe
 * Date: 9/21/2019
 * Time: 5:16 AM
 */
//Function used to check for duplicate information in the database
function checkDup($pdo, $sql, $userentry)
{
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $userentry);
        $stmt->execute();
        return $stmt->rowCount();
    }catch (PDOException $e)
    {
        exit();
    }
}
function checkLogin()
{
    if(!isset($_SESSION['ID']))
    {
        echo "<p class='error'>This page requires authentication.  Please sign in to view details.</p>";
        require_once "foot.php";
        exit();
    }
}