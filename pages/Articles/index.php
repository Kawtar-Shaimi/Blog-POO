<?php

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

use Src\Models\Article;

$articles = new Article();
$articles = $articles->getArticles();

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
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">Les plus connus</h1>

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