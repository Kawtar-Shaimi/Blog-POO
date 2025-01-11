<?php

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

use Src\Models\Article;
use Src\Models\Validator;

Validator::validateLogedInUser();

$id_user = $_COOKIE['user_id'] ?? ($_SESSION['user_id'] ?? null);

$articles = new Article();
$articles = $articles->getUserArticles($id_user);

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['deleteArticle'])){

        Validator::validateCsrf();

        $id_article = (int) $_POST['id_article'];
        $userRole = $_COOKIE['user_role'] ?? ($_SESSION['user_role'] ?? null);

        $article = new Article();
        $article->deleteArticle($id_article, $userRole);
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles - KSBlog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

   
    <?php include_once "../../layouts/header.php";?>

    
    <section class="container mx-auto p-6 relative">
        <?php 
        if($message){
            echo "
                <div class='max-w-3xl mx-auto bg-green-600 rounded-lg my-5 py-5 ps-5'>
                    <p class='text-white font-bold'>$message</p>
                </div>
            ";
        }
        ?>
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">My Articles</h1>

            <a href="./ajouterArticle.php" class="absolute cursor-pointer top-7 bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">Ajouter un article</a>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php
            if ($articles->num_rows > 0) {
                while ($row = $articles->fetch_assoc()) {
                    echo "
                        <div class='bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300'>
                            <img src='../." . htmlspecialchars($row["img"]) . "' alt='Image Article 1' class='w-full h-48 object-cover'>
                            <div class='p-6'>
                                <h2 class='text-2xl font-semibold text-blue-600 mb-4'>" . htmlspecialchars($row["titre"]) . "</h2>
                                <p class='text-gray-700 mb-4 break-all'>". substr(strip_tags(preg_replace('/<\/?(p|div|br|h[1-6]|li|ol|ul)[^>]*>/', " ", $row["content"])), 0, 200) ."...</p>
                                <a href='./afficherArticle.php?id=" . htmlspecialchars($row["id_article"]) . "' class='text-blue-500 hover:underline font-semibold'>Lire la suite</a>
                            </div>
                            <div class='flex gap-2 px-2 py-2'>
                                <a href='./updateArticle.php?id=" . htmlspecialchars($row["id_article"]) . "' class='text-blue-900 h-5 cursor-pointer'>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 576 512' class='text-blue-900 h-5 cursor-pointer'>
                                        <path fill='currentColor' d='M570.1 128.1c-9.4-9.4-24.6-9.4-33.9 0L504 150.1 361.9 8 384 0c10.7 0 20.5 4.1 28.1 11.7l157.9 157.9c9.4 9.4 9.4 24.6 0 33.9l-22.1 22.1c-9.4 9.4-24.6 9.4-33.9 0L320 63.9 63.9 320 0 512l192-63.9 256-256-42.1-42.1c-9.4-9.4-9.4-24.6 0-33.9l22.1-22.1c9.4-9.4 24.6-9.4 33.9 0l42.1 42.1 256-256 42.1 42.1c9.4 9.4 9.4 24.6 0 33.9l-22.1 22.1z'/>
                                    </svg>
                                </a>
                                <form method='POST'>
                                    <input type='hidden' name='csrf_token' value='". htmlspecialchars($_SESSION['csrf_token']) . "'>
                                    <input type='hidden' value='". htmlspecialchars($row["id_article"]) . "' name='id_article'>
                                    <button name='deleteArticle' type='submit' class='text-blue-900 h-5 cursor-pointer'>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' class='w-5 h-5'>
                                            <path fill='currentColor' d='M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z'/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    ";
                }
            }
            ?>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white p-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 KSBlog. Tous droits réservés.</p>
        </div>
    </footer>

</body>

</html>