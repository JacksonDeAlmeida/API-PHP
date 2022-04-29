<?php
  include('lib/functions.service.php');
  
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: *");
  header("Access-Control-Allow-Headers: Content-Type, Origin, Authorization");
  header('content-type: application/json');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);



  $url = explode('/', $_SERVER['REQUEST_URI']);
  // echo $url[4];
  
  if ($url[3] == 'api') {

    // ### USER ###
    if ($url[4] == 'user') {
      if($_SERVER['REQUEST_METHOD']=="GET") {
        if(isset($url[5]) && is_numeric($url[5]) == 1) {
          $json = getUserById($url[5]);
          if(empty($json))
          header("HTTP/1.1 404 Not Found");
          echo json_encode($json);
        } else if (isset($url[5]) && is_numeric($url[5]) == 0) {
          $json = getUserByEmail($url[5]);
          if(empty($json))
          header("HTTP/1.1 404 Not Found");
          echo json_encode($json);
        } else {
          $json = getAllUsers();
          echo json_encode($json);
        }
      }
    
      if($_SERVER['REQUEST_METHOD']=="POST") {
        
        $data = json_decode( file_get_contents( 'php://input' ), true );
        
        $email = $data['email'];
        $password = $data['password'];
        $crypt = md5($password, false);
    
        $user = getUserByEmail($email);
        // echo $user[0]['password'];

        if ($user) {
          if ($user[0]['password'] == $crypt) {
            $header = [
              'typ' => 'JWT',
              'alg' => 'HS256'
            ];

            $payload = [
              'exp' => (new DateTime("now"))->getTimestamp(),
              'uid' => 1,
              'email' => $email,
              'userId' => $user[0]['id']
            ];

            $header = json_encode($header);
            $header = base64_encode($header);
            $payload = json_encode($payload);
            $payload = base64_encode($payload);
            $key = "timeto";

            $sign = hash_hmac('sha256', $header . "." . $payload, $key, true);
            $sign = base64_encode($sign);
            $token = $header . "." . $payload . "." . $sign;

            $message['access_token'] = $token;
            echo json_encode($message);
          } else {
            $message['login'] = false;
            echo json_encode($message);
          }
        } else {
        $username = $data['username'];

          if (!empty($username) AND !empty($email) AND !empty($password)) {
            // echo "Registrado!";
            $json = addUser($username, $email, $crypt);
            echo json_encode($json);
          } else {
            $message['data'] = "Dados incorretos/incompletos!";
            $message['error'] = true;
            echo json_encode($message);
          }
        }

      }
    
      if($_SERVER['REQUEST_METHOD']=="PUT") {
        if(isset($url[5]) && is_numeric($url[5]) == 1) {
          $data = json_decode( file_get_contents( 'php://input' ), true );

          $id =  $url[5];
          $username = $data['username'];
          $password = $data['password'];
      
          $json = '';
      
          if (!empty($username) AND !empty($password)) {
            $json = updadeUserById($id, $username, $password);
            header("HTTP/1.1 404 Not Found");
            echo json_encode($json);
            // if ($json['data'][0]['editavel'] == 1) {
            //     echo "Dados atualizado";
            //   } else {
            //     echo "Usuário não pode ser editada";
            //   }
          } else {
            $message['data'] = "Dados incorretos/incompletos!";
            echo $message['data'];
          }
        } else {
          echo "Erro ao atualizar noticia";
        }
      }
    
      if($_SERVER['REQUEST_METHOD']=="DELETE") {
        if(isset($url[5]) && is_numeric($url[5]) == 1) {

          $data = json_decode( file_get_contents( 'php://input' ), true );
          
          $id = $url[5];
          
          $json = deleteUserById($id);
          echo json_encode($json);
        } else {
          echo "Erro ao tentar deletar";
        }
      }
    }

    // ## EVENTS ##
    if ($url[4] == 'events') {

      if($_SERVER['REQUEST_METHOD']=="GET") {
        if(isset($url[5]) && is_numeric($url[5]) == 1) {
          $json = getEventsByUser($url[5]);
          if(empty($json))
          header("HTTP/1.1 404 Not Found");
          echo json_encode($json);
        } else {
          $json = getAllEvents();
          echo json_encode($json);
        }
      }

      if($_SERVER['REQUEST_METHOD']=="POST") {
        $data = json_decode( file_get_contents( 'php://input' ), true );
        $userId = $url[5];
        $checkUser = getUserById($userId);

        if ($checkUser) {
          $name = $data['name'];
          $description = $data['description'];
          $initialDatetime = $data['initialDatetime'];
          $finalDatetime = $data['finalDatetime'];
      
          if (!empty($name) AND !empty($description) AND !empty($initialDatetime) AND !empty($finalDatetime)) {
            $json = addEvent($userId, $name, $description, $initialDatetime, $finalDatetime);
            echo json_encode($json);
          } else {
            $message['data'] = "Dados incorretos/incompletos!";
            echo $message['data'];
          }
        } else {
          $json['message'] = "Invalid user";
          echo json_encode($json);
        }
      }

      if($_SERVER['REQUEST_METHOD']=="PUT") {
        if(isset($url[5]) && is_numeric($url[5]) == 1) {
          $data = json_decode( file_get_contents( 'php://input' ), true );

          $userId =  $url[5];
          $eventId = $data['eventId'];

          if (getUserById($userId) AND getEventById($eventId) AND checkUserEvent($userId, $eventId)) {
            $name = $data['name'];
            $description = $data['description'];
            $initialDatetime = $data['initialDatetime'];
            $finalDatetime = $data['finalDatetime'];
        
            $json = '';
            if (!empty($name) AND !empty($description) AND !empty($initialDatetime) AND !empty($finalDatetime)) {
              $json = updadeEventById($eventId, $name, $description, $initialDatetime, $finalDatetime);
              header("HTTP/1.1 404 Not Found");
            } else {
              $message['data'] = "Dados incorretos/incompletos!";
              echo $message['data'];
            }
          } else {
            $message['data'] = "Dados incorretos/incompletos!";
            echo $message['data'];
          }

          
        } else {
          echo "Erro ao atualizar noticia";
        }
      }

      if($_SERVER['REQUEST_METHOD']=="DELETE") {
        if(isset($url[5]) && is_numeric($url[5]) == 1) {

          $data = json_decode( file_get_contents( 'php://input' ), true );
          $userId = $url[5];
          $eventId = $data['eventId'];
          
          $json = deleteEventById($userId, $eventId);
          echo json_encode($json);
        } else {
          echo "Erro ao tentar deletar";
        }
      }

    }    
  }

  