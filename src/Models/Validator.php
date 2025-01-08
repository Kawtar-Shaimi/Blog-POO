<?php 

namespace Src\Models;

class Validator{

    public static function validateCsrf(){
        if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            echo "Session expired or invalid request. Please refresh the page.";
            exit();
        }
        unset($_SESSION['csrf_token']);
    }

    public static function validateAdmin(){
        if((!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) && (!isset($_COOKIE['user_id']) || !isset($_COOKIE['user_role']))){
            header("Location: /Blog_POO/pages/Auth/login.php");
            exit;
        }else{
            if(isset($_COOKIE['user_role'])){
                if($_COOKIE['user_role'] != "admin"){
                    header("Location: /Blog_POO/index.php");
                    exit;
                }
            }else{
                if($_SESSION['user_role'] != "admin"){
                    header("Location: /Blog_POO/index.php");
                    exit;
                }
            }
        }
    }

    public static function validateLogedInUser(){
        if((!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) && (!isset($_COOKIE['user_id']) || !isset($_COOKIE['user_role']))){
            header("Location: /Blog_POO/pages/Auth/login.php");
            exit;
        }
    }

    public static function validateLogedOutUser(){
        if ((isset($_COOKIE['user_id']) && isset($_COOKIE['user_role'])) || (isset($_SESSION['user_id']) && isset($_SESSION['user_role']))) {
            if(isset($_COOKIE['user_role'])){
                if($_COOKIE['user_role'] === "user"){
                    header("Location: /Blog_POO/index.php");
                    exit;
                }else {
                    header("Location: /Blog_POO/pages/Admin/Users/index.php");
                }
            }else{
                if($_SESSION['user_role'] === "user"){
                    header("Location: /Blog_POO/index.php");
                    exit;
                }else {
                    header("Location: /Blog_POO/pages/Admin/Users/index.php");
                }
            }
        }
    }

    public static function validateLogin($email, $pass){

        $isValid = true;

        if (empty($email) || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['emailErr'] = "Email is required and must be 100 characters or less.";
            $isValid = false;
        }

        if (empty($pass) || strlen($pass) < 8) {
            $_SESSION["passErr"] = "Password is required and must be strong.";
            $isValid = false;
        }
        
        return $isValid;
    }

    public static function validateSignup($name, $email, $pass, $confirmPass, $conn){

        $isValid = true;

        if (empty($name) || strlen($name) > 30) {
            $_SESSION['nameErr'] = "Name is required and must be 30 characters or less.";
            $isValid = false;
        }

        if (empty($email) || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['emailErr'] = "Email is required and must be 100 characters or less.";
            $isValid = false;
        }else{
            $sql = "SELECT email FROM users where email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows >0){
                $_SESSION["emailErr"]="This email already existe";
                $isValid = false;
            }
        }

        if (empty($pass) || strlen($pass) < 8) {
            $_SESSION["passErr"] = "Password is required and must be strong.";
            $isValid = false;
        }

        if(empty($confirmPass)){
            $_SESSION["confirmPassErr"] = "Confirm password is required.";
        }else{
            if($pass != $confirmPass){
                $_SESSION["confirmPassErr"] = "Passwords are Not matching!";
                $isValid = false;
            }
        }

        return $isValid;
    }

    public static function validateCreateUser($name, $email, $pass, $confirmPass, $role, $conn){

        $isValid = true;

        if (empty($name) || strlen($name) > 30) {
            $_SESSION['nameErr'] = "Name is required and must be 30 characters or less.";
            $isValid = false;
        }

        if (empty($email) || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['emailErr'] = "Email is required and must be 100 characters or less.";
            $isValid = false;
        }else{
            $sql = "SELECT email FROM users where email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows >0){
                $_SESSION["emailErr"]="This email already existe";
                $isValid = false;
            }
        }

        if (empty($pass) || strlen($pass) < 8) {
            $_SESSION["passErr"] = "Password is required and must be strong.";
            $isValid = false;
        }

        if(empty($confirmPass)){
            $_SESSION["confirmPassErr"] = "Confirm password is required.";
        }else{
            if($pass != $confirmPass){
                $_SESSION["confirmPassErr"] = "Passwords are Not matching!";
                $isValid = false;
            }
        }

        if (empty($role) || ($role != "user" && $role != "admin")) {
            $_SESSION["roleErr"] = "Role is required and must be user or admin.";
            $isValid = false;
        }

        return $isValid;
    }

    public static function validateUpdateUser($name, $email, $oldEmail, $role, $conn){

        $isValid = true;

        if (empty($name) || strlen($name) > 30) {
            $_SESSION['nameErr'] = "Name is required and must be 30 characters or less.";
            $isValid = false;
        }

        if (empty($email) || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['emailErr'] = "Email is required and must be 100 characters or less.";
            $isValid = false;
        }else{
            $sql = "SELECT email FROM users where email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                if($result->fetch_column() != $oldEmail){
                    $_SESSION["emailErr"] = "This email already existe";
                    $isValid = false;
                }
            }
        }

        if (empty($role) || ($role != "user" && $role != "admin")) {
            $_SESSION["roleErr"] = "Role is required and must be user or admin.";
            $isValid = false;
        }

        return $isValid;
    }

    public static function validateCreateArticle($title, $content, $img, $categories){

        $isValid = true;

        if (empty($title) || strlen($title) > 100) {
            $_SESSION['titleErr'] = "Title is required and must be 100 characters or less.";
            $isValid = false;
        }
    
        if (empty($content)) {
            $_SESSION['contentErr'] = "Content is required.";
            $isValid = false;
        }
    
        if (empty($img) || $img['error'] != 0) {
            $_SESSION["imgErr"] = "Image is required.";
            $isValid = false;
        }
    
        if(count($categories) <= 0){
            $_SESSION["categoriesErr"] = "Categories is required.";
        }

        return $isValid;
    }

    public static function validateUpdateArticle($title, $content){

        $isValid = true;

        if (empty($title) || strlen($title) > 100) {
            $_SESSION['titleErr'] = "Title is required and must be 100 characters or less.";
            $isValid = false;
        }
    
        if (empty($content)) {
            $_SESSION['contentErr'] = "Content is required.";
            $isValid = false;
        }

        return $isValid;
    }

    public static function validateCategory($category_name){

        $isValid = true;

        if (empty($category_name) || strlen($category_name) > 100) {
            $_SESSION['categoryNameErr'] = "Category name is required and must be 100 characters or less.";
            $isValid = false;
        }

        return $isValid;
    }

    public static function validateComment($content){

        $isValid = true;

        if (empty($content)) {
            $_SESSION['contentErr'] = "Content is required.";
            $isValid = false;
        }

        return $isValid;
    }

}