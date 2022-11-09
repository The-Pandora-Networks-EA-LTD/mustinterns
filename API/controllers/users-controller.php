<?php
      require_once 'database.php';
      require_once '../model/response.php';

      if ($_SERVER['REQUEST_METHOD'] === 'GET') {
          try {
            $query = $DB->prepare("SELECT * FROM users");
            $query->execute();

            $rowCount = $query->rowCount();
            $usersArray = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)){
              extract($row);
                  $users = array(
                      "id" => $id,
                      "username" => $username,
                      "password" => $password,
                      "status" => $status,
                      "timestamp" => $timestamp,
                      "balance" => $balance
                  );
                  $usersArray[] = $users;
            }
            sendResponse (200, true, "", $usersArray);
            exit;
          } catch (PDOException $ex) {
            sendResponse (500, false, "message: ".$ex);
            exit;
          }


      } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        // echo $rawPostData;
        // var_dump($jsonData->username);
        $username = $jsonData->username;
        $password = $jsonData->password;
        $balance = $jsonData->balance;

        $query = $DB->prepare("INSERT INTO users(username, password, balance) VALUES (:name, :pass, :bal)");
        $query->bindParam(':name', $username, PDO::PARAM_STR);
        $query->bindParam(':pass', $password, PDO::PARAM_STR);
        $query->bindParam(':bal', $balance, PDO::PARAM_INT);
        $query->execute();

        $rowCount = $query->rowCount();


        if ($rowCount = 0) {
          sendResponse (500, false, "internal server error");
          exit;
        }

        $lastID  = $DB->lastInsertId();

        $query = $DB->prepare('SELECT * FROM users WHERE id = :userid');
        $query->bindParam(':userid', $lastID, PDO::PARAM_INT);
        $query->execute();

        $rowCount = $query->rowCount();

        if ($rowCount = 0) {
          sendResponse (500, false, "internal server error");
          exit;
        }
        
        $usersArray = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
          extract($row);
              $users = array(
                  "id" => $id,
                  "username" => $username,
                  "password" => $password,
                  "status" => $status,
                  "timestamp" => $timestamp,
                  "balance" => $balance
              );
              $usersArray[] = $users;
        }
        sendResponse (200, true, "", $usersArray);
        exit;

      } catch (PDOException $ex) {
        sendResponse (400, false, "message: ".$ex);
        exit;
        }

      } else {
        sendResponse (405, false, "request method not allowed");
        exit;
      }
