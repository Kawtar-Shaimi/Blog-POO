<?php 

namespace Src\Models;

use Src\DB\DataBase;
use Src\Models\Validator;
use Exception;

class Article{
    
    private $db;

    public function __construct(){
        $this->db = new DataBase();
    }

    public function getArticles(){
        try{
            
            $sql = "SELECT * FROM articles";
            $result = $this->db->conn->query($sql);
            return $result;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getUserArticles($id_user){
        try{
            
            $sql = "SELECT * FROM articles WHERE id_user = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getArticle($id_article){
        try{
            
            $sql = "SELECT * FROM articles WHERE id_article = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_article);
            $stmt->execute();
            $result = $stmt->get_result();
            $article = $result->fetch_assoc();
            return $article;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function createArticle($title, $content, $img, $categories, $id_user){

        if(!Validator::validateCreateArticle($title, $content, $img, $categories)){
            header("Location: /Blog_POO/pages/Articles/createArticle.php");
            exit;
        }

        try{

            $imgName = $img['name'];
            $uniqueImgName = uniqid('article-') . '-' . basename($imgName);
            $imgPath = "./images/articles/$uniqueImgName";

            $sql = "INSERT INTO articles (titre, content, img, id_user) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Database error: " . $this->db->conn->error);
            }

            $stmt->bind_param("sssi", $title, $content, $imgPath, $id_user);
            $stmt->execute();
            $id_article = $stmt->insert_id;

            move_uploaded_file($img['tmp_name'], __DIR__ . "/../.$imgPath");
            
            foreach($categories as $id_category){
                $sql = "INSERT INTO categoryArticles (id_category, id_article) VALUES (?, ?)";
                $stmt = $this->db->conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception("Database error: " . $this->db->conn->error);
                }

                $stmt->bind_param("ii", $id_category, $id_article);
                $stmt->execute();
            }

            $_SESSION['message'] = "Article Added Successfully";
            $stmt->close();
            $this->db->conn->close();
            
            header("Location: /Blog_POO/pages/Articles/userArticles.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function updateArticle($id_article, $title, $content, $img, $oldImg, $categories, $role){
        if(!Validator::validateUpdateArticle($title, $content)){
            header("Location: /Blog_POO/pages/Articles/updateArticle.php?id=$id_article");
            exit;
        }

        try{
            if ($img['name']) {
                $imgName = $img['name'];
                $uniqueImgName = uniqid('article-') . '-' . basename($imgName);
                $imgPath = "./images/articles/$uniqueImgName";
            }else{
                $imgPath = $oldImg;
            }

            $sql = "UPDATE articles SET titre = ?, content = ?, img = ? WHERE id_article = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sssi", $title, $content, $imgPath, $id_article);
            $stmt->execute();

            if($imgPath != $oldImg){
                $oldImgPath = __DIR__ . "/../.$oldImg";
                if (file_exists($oldImgPath)) {
                    if (unlink($oldImgPath)) {
                        move_uploaded_file($img['tmp_name'], __DIR__ . "/../.$imgPath");
                    }
                }
            }

            if($categories){
                if(count($categories) > 0){
                    $sql = "DELETE FROM categoryArticles WHERE id_article = ?";
                    $stmt = $this->db->conn->prepare($sql);
                    
                    if (!$stmt) {
                        throw new Exception("Database error: " . $this->db->conn->error);
                    }

                    $stmt->bind_param("i", $id_article);
                    $stmt->execute();
                    foreach($categories as $id_category){
                        $sql = "INSERT INTO categoryArticles (id_category, id_article) VALUES (?, ?)";
                        $stmt = $this->db->conn->prepare($sql);
        
                        if (!$stmt) {
                            throw new Exception("Database error: " . $this->db->conn->error);
                        }
        
                        $stmt->bind_param("ii", $id_category, $id_article);
                        $stmt->execute();
                    }
                }
            }

            $sql = "DELETE FROM categoryArticles WHERE id_article = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_article);
            $stmt->execute();

            foreach($categories as $id_category){
                $sql = "INSERT INTO categoryArticles (id_category, id_article) VALUES (?, ?)";
                $stmt = $this->db->conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception("Database error: " . $this->db->conn->error);
                }

                $stmt->bind_param("ii", $id_category, $id_article);
                $stmt->execute();
            }

            $_SESSION['message'] = "Article updated successfully!";
            $stmt->close();
            $this->db->conn->close();

            if($role === "admin"){
                header("Location: /Blog_POO/pages/Admin/Articles/index.php");
                exit;
            }else{
                header("Location: /Blog_POO/pages/Articles/userArticles.php");
                exit;
            }
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteArticle($id_article, $role){
        try{
            $sql = "SELECT img FROM articles WHERE id_article = ?";
            $stmt = $this->db->conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Database error: " . $this->db->conn->error);
            }

            $stmt->bind_param("i", $id_article);
            $stmt->execute();
            $result = $stmt->get_result();
            $filePath = __DIR__ . "/../." . $result->fetch_column();

            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    $sql = "DELETE FROM articles WHERE id_article = ?";
                    $stmt = $this->db->conn->prepare($sql);
                    
                    if (!$stmt) {
                        throw new Exception("Database error: " . $this->db->conn->error);
                    }

                    $stmt->bind_param("i", $id_article);
                    $stmt->execute();
                    $_SESSION['message'] = "Article Deleted Successfully";
                    $stmt->close();
                    $this->db->conn->close();

                    if($role === "admin"){
                        header("Location: /Blog_POO/pages/Admin/Articles/index.php");
                        exit;
                    }else{
                        header("Location: /Blog_POO/pages/Articles/userArticles.php");
                        exit;
                    }
                }
            }
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getArticleCategories($id_article){
        try{
            $sql = "SELECT category_name FROM categories
                    INNER JOIN categoryArticles
                    ON categories.id_category = categoryarticles.id_category
                    where categoryarticles.id_article = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_article);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getArticleComments($id_article){
        try{
            $sql = "SELECT * FROM comments
                    INNER JOIN users
                    ON comments.id_user = users.id_user
                    WHERE id_article = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_article);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getArticleLikeCount($id_article){
        try{
            $sql = "SELECT COUNT(*) as likes FROM likes WHERE id_article = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_article);
            $stmt->execute();
            $result = $stmt->get_result();
            $likes = $result->fetch_column();
            return $likes;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function isLiked($id_user, $id_article){
        try{
            $sql = "SELECT * FROM likes where id_article = ? AND id_user = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("ii", $id_article, $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $this->db->conn->close();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

}