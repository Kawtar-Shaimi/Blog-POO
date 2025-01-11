<?php 

namespace Src\Models;

use Src\DB\DataBase;
use Src\Models\Validator;
use Exception;

class Comment{

    private $db;
    private $content;
    private $id_article;
    private $id_user;

    public function __construct(){
        $this->db = new DataBase();
    }

    public function getComments(){
        try{

            $sql = "SELECT * FROM comments";
            $result = $this->db->conn->query($sql);
            return $result;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function createComment($content, $id_article, $id_user){

        if(!Validator::validateComment($content)){
            header("Location: /Blog_POO/pages/Articles/afficherArticle.php?id=$id_article");
            exit;
        }

        try{
            $sql = "INSERT INTO comments (content, id_article, id_user) VALUES (?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sii", $content, $id_article, $id_user);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Comment added successfully!";

            header("Location: /Blog_POO/pages/Articles/afficherArticle.php?id=$id_article");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteComment($id_comment, $id_article = null){
        try{
            $sql = "DELETE FROM comments WHERE id_comment = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_comment);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Comment deleted successfully!";

            if($id_article){
                header("Location: /Blog_POO/pages/Articles/afficherArticle.php?id=$id_article");
                exit;
            }else{
                header("Location: /Blog_POO/pages/Admin/Comments/index.php");
                exit;
            }

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}