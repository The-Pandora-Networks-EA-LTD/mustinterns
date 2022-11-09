<?php
    header('Content-type: application/json');
      function sendResponse ($httpstatusCode, $success, $message = '', $data = []) {
            $response = array(
                  "statusCode" => (int) $httpstatusCode,
                  "success" => (bool)$success,
                  "message" => $message,
                  "data" => $data
            );

            echo json_encode($response);

      }
