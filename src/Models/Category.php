<?php 

namespace Src\Models;

use Src\DB\DataBase;
use Src\Models\Validator;
use Exception;

class Category{
    
    private $db;
    private $id_category;
    private $category_name;

    public function __construct(){
        $this->db = new DataBase();
    }

    public function getCategories(){
        try{

            $sql = "SELECT * FROM categories";
            $result = $this->db->conn->query($sql);
            return $result;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getCategory($id_category){
        try{

            $sql = "SELECT * FROM categories WHERE id_category = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_category);
            $stmt->execute();
            $result = $stmt->get_result();
            $category = $result->fetch_assoc();
            $stmt->close();
            $this->db->conn->close();
            return $category;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function createCategory($category_name){
        if(!Validator::validateCategory($category_name)){
            header("Location: /Blog_POO/pages/Admin/Categories/createCategory.php");
            exit;
        }

        try{
            $sql = "INSERT INTO categories (category_name) VALUES (?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("s", $category_name);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Category added successfully!";

            header("Location: /Blog_POO/pages/Admin/Categories/index.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function updateCategory($category_name, $id_category){
        if(!Validator::validateCategory($category_name)){
            header("Location: /Blog_POO/pages/Admin/Categories/updateCategory.php?id=$id_category");
            exit;
        }

        try{
            $sql = "UPDATE categories SET category_name = ? WHERE id_category = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("si", $category_name, $id_category);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Category updated successfully!";

            header("Location: /Blog_POO/pages/Admin/Categories/index.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteCategory($id_category){
        try{
            $sql = "DELETE FROM categories WHERE id_category = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_category);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Category deleted successfully!";

            header("Location: /Blog_POO/pages/Admin/Categories/index.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}