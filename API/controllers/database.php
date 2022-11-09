<?php

    $servername = "localhost"; // 172.0.0.1 0.0.0.0
    $username = "root";
    $password = "";
    $dbname = "login_test";

    try {
      $DB = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
      $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo "Connected successfully";
    } catch(PDOException $ex) {
      echo "Connection failed: " . $ex->getMessage();
    }
