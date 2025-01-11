<?php 

namespace Src\Models;

use Src\DB\DataBase;
use Exception;

class Like{
    
    private $db;

    public function __construct(){
        $this->db = new DataBase();
    }

    public function createLike($id_article, $id_user){
        try{
            $sql = "INSERT INTO likes (id_article, id_user) VALUES (?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("ii", $id_article, $id_user);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            header("Location: /Blog_POO/pages/Articles/afficherArticle.php?id=$id_article");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteLike($id_article, $id_like){
        try{
            $sql = "DELETE FROM likes WHERE id_like = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_like);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            header("Location: /Blog_POO/pages/Articles/afficherArticle.php?id=$id_article");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}