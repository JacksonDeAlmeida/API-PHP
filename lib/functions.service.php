<?php
include 'database.php';
// require(__DIR__ . '/database.php');

function getAllUsers() {
    $pdo = Database::connect();
    $sql = "SELECT * FROM user";
    
    try {
        $query = $pdo->prepare($sql);
        $query->execute();
        $allUsers = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    Database::disconnect();
    return $allUsers;
}

function getUserById($id) {
    $pdo = Database::connect();
    $sql = "SELECT * FROM user where id = ? ";

    try {
        $query = $pdo->prepare($sql);
        $query->execute([$id]);
        $userInfo = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    Database::disconnect();
    return $userInfo;
}

function getUserByEmail($email) {
    $pdo = Database::connect();
    $sql = "SELECT * FROM user where email = ? ";

    try {
        $query = $pdo->prepare($sql);
        $query->execute([$email]);
        $userInfo = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    Database::disconnect();
    return $userInfo;
}

function updadeUserById($id, $name, $password) {
    
    $pdo = Database::connect();
    $sql = "UPDATE user SET username = ?, password = ? WHERE id = ?";
    $status = [];

    try {
        $query = $pdo->prepare($sql);
        $result = $query->execute([$name, $password, $id]);
        
        if ($result) {
            $status['message'] = "Data updated";
            // $status['data'] = getUserById($id);
        } else {
            $status['message'] = "Data is not updated";
        }
    } catch (PDOException $e) {
        $status['message'] = $e->getMessage();
    }

    Database::disconnect();
    return $status;
}


function addUser($username, $email, $password) {

    $pdo = Database::connect();
    $sql = "INSERT INTO user (`username`, `email`, `password`)
            VALUES(?, ?, ?)";
    $status = [];

    try {

        $query = $pdo->prepare($sql);
        $result = $query->execute([$username, $email, $password]);
        if($result) {
            $status['message'] = "Data inserted";
        }
        else {
            $status['message'] = "Data is not inserted";
        }

    } catch (PDOException $e) {
        $status['message'] = $e->getMessage();
    }

    Database::disconnect();
    return $status;
}

function deleteUserById($id) {
    $pdo = Database::connect();
    $sql = "";

    // $checkEditavel['data'] = getUserById($id);
    
    $sql ="DELETE FROM user where id = ?";
    // if ($checkEditavel['data'][0]['editavel'] == 1) {
    // }

    $status = [];

    try {
        $result = '';
        if (!empty($sql)) {
            $query = $pdo->prepare($sql);
            $result = $query->execute([$id]);
        }
        if($result)
        {
            $status['message'] = "Data deleted";
        }
        else{
            $status['message'] = "Data is not deleted";
        }

    } catch (PDOException $e) {

        $status['message'] = $e->getMessage(); 
    }

    Database::disconnect();
    return $status;
}

// ===================

function getAllEvents() {
    $pdo = Database::connect();
    $sql = "SELECT * FROM events";

    try {
        $query = $pdo->prepare($sql);
        $query->execute();
        $allCategory = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    Database::disconnect();
    return $allCategory;
}

function getEventsByUser($id) {
    $pdo = Database::connect();

    $sql = "SELECT * FROM events where idUser = ? ";

    try {
        $query = $pdo->prepare($sql);
        $query->execute([$id]);
        $categoryInfo = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    Database::disconnect();
    return $categoryInfo;
}

function getEventById($id) {
    $pdo = Database::connect();

    $sql = "SELECT * FROM events where id = ? ";

    try {
        $query = $pdo->prepare($sql);
        $query->execute([$id]);
        $eventInfo = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    Database::disconnect();
    return $eventInfo;
}

function checkUserEvent($userId, $eventId) {
    $pdo = Database::connect();

    $sql = "SELECT * FROM events where idUser = ? AND id = ?";

    try {
        $query = $pdo->prepare($sql);
        $query->execute([$userId, $eventId]);
        $eventInfo = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    Database::disconnect();
    return $eventInfo;
}

function updadeEventById($eventId, $name, $description, $initialDatetime, $finalDatetime) {

    $pdo = Database::connect();
    $sql = "UPDATE events SET
                name = ?,
                description = ?,
                initialDatetime = ?,
                finalDatetime = ?
            WHERE id = ?";
    $status = [];

    try {
      $query = $pdo->prepare($sql);
      $result = $query->execute([$name, $description, $initialDatetime, $finalDatetime, $eventId]);

        if ($result) {
            $status['message'] = "Data updated";
            $status['data'] = getEventById($eventId);
        } else {
            $status['message'] = "Data is not updated";
        }
    } catch (PDOException $e) {
      $status['message'] = $e->getMessage();
    }

    Database::disconnect();
    return $status;
}


function addEvent($userId, $name, $description, $initialDatetime, $finalDatetime) {

    $pdo = Database::connect();
    $sql = "INSERT INTO events (`name`, `description`, `initialDatetime`, `finalDatetime`,`idUser`)
            VALUES (?, ?, ?, ?, ?)";
    $status = [];

    try {
      $query = $pdo->prepare($sql);
      $result = $query->execute([$name, $description, $initialDatetime, $finalDatetime, $userId]);
      if($result) {
          $status['message'] = "Event created";
      }
      else {
          $status['message'] = "Event is not created";
      }
    } catch (PDOException $e) {
        $status['message'] = $e->getMessage();
    }

    Database::disconnect();
    return $status;
}

function deleteEventById($userId, $eventId) {
    $pdo = Database::connect();
    $sql = "DELETE FROM events where idUser = ? AND id = ?";

    $status = [];

    try {
        $query = $pdo->prepare($sql);
        $result = $query->execute([$userId, $eventId]);
        // if (!empty($sql)) {
        //     $query = $pdo->prepare($sql);
        //     $result = $query->execute([$userId, $eventId]);
        // }
        if ($result) {
            $status['message'] = "Data deleted";
        } else {
            $status['message'] = "Data is not deleted";
        }
    } catch (PDOException $e) {
        $status['message'] = $e->getMessage(); 
    }

    Database::disconnect();
    return $status;
}