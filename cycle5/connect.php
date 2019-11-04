<?php
/**
 * Created by PhpStorm
 * User: jbedw
 * Date: 10/21/2019
 * Time: 3:03 PM
 */
//Connect to the server
try{
    $connString = "mysql:host=localhost;dbname=csci22502sp18";
    $user = "csci22502sp18";
    $pass = "csci22502sp18!";
    $pdo = new PDO($connString,$user,$pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    die( $e->getMessage() );
}