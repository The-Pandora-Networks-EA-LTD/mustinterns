<?php

      require_once 'database.php';
      require_once '../model/response.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      try {
        if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
          sendResponse (400, false, "content not json");
          exit;
        }

        $rawPostData = file_get_contents('php://input');

        if(!$jsonData = json_decode($rawPostData)) {
          sendResponse (400, false, "not valid json");
          exit;
        }

        $username = $jsonData->username;
        $password = $jsonData->password;


        $query = $DB->prepare('SELECT * from users where username = :uname');
        $query->bindParam(':uname', $username, PDO::PARAM_STR);
        $query->execute();

        $rowCount = $query->rowCount();
        // echo $rowCount;
        if ($rowCount === 0) {
          sendResponse (401, false, "username or password is incorrect");
          exit;
        }
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($password, $row['password'])) {
          sendResponse (401, false, "username or password is incorrects");
          exit;
        }

        sendResponse (201, true, "login success");
        exit;
      } catch (PDOException $ex) {
        sendResponse (500, false, "message: ".$ex);
        exit;
        }

      } else {
        sendResponse (405, false, "request method not allowed");
        exit;
      }
