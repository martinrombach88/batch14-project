<?php
session_start();
require_once("Manager.php");
class UserManager extends Manager {
    public function __construct($userid = 0) {
        parent::__construct();
        $this->userid = $userid;
    }
    
    public function getUsers(){
        $get = $this->_connexion->query("SELECT id, firstName, lastName, userName, password, role, phoneNumber FROM user");
        $getUsers= $get->fetchAll(PDO::FETCH_ASSOC);
        $get->closeCursor();
        return $getUsers;
    }

    // public function filterUser($username){
    //     $req = $this->_connexion->prepare("SELECT id, firstName, lastName, userName, password, role, phoneNumber FROM user WHERE userName = :userName");
    //     $getUsers= $req->fetchAll(PDO::FETCH_ASSOC);
    //     $req->closeCursor();
    //     return $getUsers;
    // }

    public function getUser() {
        $req = $this->_connexion->query('SELECT * FROM user WHERE id = 1');
        // $req->bindParam(1, $this->_user_id, PDO::PARAM_INT);
        // $req->execute();
        $user = $req->fetch(PDO::FETCH_ASSOC);
        $req->closeCursor();
        return $user;
    }

    public function logInUser($userName, $pwd){

        $req = $this->_connexion->prepare("SELECT id, userName, password, role FROM user WHERE userName=? ");
        $req->bindParam(1,$userName, PDO::PARAM_STR);
        $req->execute();
        $user = $req->fetch(PDO::FETCH_ASSOC);
        $req->closeCursor();
        
        if ($user && password_verify($pwd, $user['password'])){
            $_SESSION['userName'] = $user['userName']; 
            $_SESSION['userId'] = $user['id'];
            $_SESSION['userRole'] = $user['role'];
            if ($_SESSION['userRole'] == 0) {
                $_SESSION['userRoleDesc'] = "admin";
            }elseif($_SESSION['userRole'] == 1) {
                $_SESSION['userRoleDesc'] = "teacher";
            }else {
                $_SESSION['userRoleDesc'] = "student";
            }
            return $user;
        } else {
            return false;
        }
    }
  
    public function delete(){
        $req = $this->_connexion->prepare("DELETE FROM user WHERE id = :userId");
        $req->bindParam("userId", $this->userid, PDO::PARAM_STR);
        $req->execute();
    }

    public function updateImage($userid, $imagePath) {
        $req = $this->_connexion->prepare("UPDATE user SET imagePath = :imagePath WHERE id = :userId"); 
        $req->bindParam("userId", $userid, PDO::PARAM_INT);
        $req->bindParam("imagePath", $imagePath, PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
        
    }
}

// $response = $db->prepare('UPDATE video_games SET comments = "and Best game ever for me!" WHERE name= "Call of Duty"');
// $response-> execute();

// Warning: Undefined variable $style in C:\xampp\htdocs\batch14-project\view\template.php on line 11

// Sorry an exception occured
// Message : SQLSTATE[HY093]: Invalid parameter number: parameter was not defined
