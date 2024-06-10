<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //ADD ACTION
    if ($_GET['action'] === 'add') {
        $username = $_POST['username'];
        if (!empty($username)) {
            //check if email is valid
            if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',$username)) {
                header('Location: /?result=error-invalid');
                    exit();
            }

            try {
                $database->insert("users", [
                    "username" => $username,
                    "mail_host" => getenv('IMAP_HOST')
                ]);
            }
            catch (PDOException $e) {
                if ($e->errorInfo[1] == 1062) {
                    header('Location: /?result=error-duplicate');
                    exit();
                 } else {
                    header('Location: /?result=error');
                    exit();
                 }
            }
            //check if user was created
            $user = $database->get("users", ['username'], ["username" => $username]);
            if ($username == $user['username'] && $database->error == false) {
                header('Location: /?result=success');
                exit();
            }
            else {
                header('Location: /?result=error-empty');
                exit();
            }
        }
        else {
            header('Location: /?result=error-empty');
            exit();
        }
    
    // DELETE ACTION
    } elseif ($_GET['action'] === 'delete') {
        $user_id = $_POST['user_id'];
        if (!empty($user_id)) {

            //check DB if user exists
            $user_db = $database->get("users", ['user_id'], ["user_id" => $user_id]);
            if ($user_db['user_id'] != $user_id) {
                header('Location: /?result=error-missing');
                exit();
            }

            try {
                $database->delete("users", ["user_id" => $user_id]);
            }
            catch (Exception $e){
                header('Location: /?result=error');
                exit();
            }
            
            header('Location: /?result=success');
            exit();
        }
        else {
            header('Location: /?result=error-empty');
            exit();
        }

    }
    else {
        header('Location: /');
        exit();
    }
}
else {
    header('Location: /');
    exit();
}

?>