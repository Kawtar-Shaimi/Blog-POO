<?php

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

use Src\Models\Article;
use Src\Models\Comment;
use Src\Models\Like;
use Src\Models\Validator;

$id_article = (int) $_GET['id'];

$articles = new Article();
$article_infos = $articles->getArticle($id_article);

$categories = $articles->getArticleCategories($id_article);

$comments = $articles->getArticleComments($id_article);

$likesCount = $articles->getArticleLikeCount($id_article);

$userId = $_COOKIE['user_id'] ?? ($_SESSION['user_id'] ?? null);

if ($userId){
    $result = $articles->isLiked( $userId, $id_article);
    $is_liked = $result->num_rows > 0;
    $id_like = $result->fetch_column();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['createLike'])){

        Validator::validateCsrf();

        $id_article = (int) $_POST['id_article'];
        $id_user = (int) $_POST['id_user'];

        $like = new Like();
        $like->createLike($id_article, $id_user);
    }

    if(isset($_POST['deleteLike'])){

        Validator::validateCsrf();

        $id_like = (int) $_POST['id_like'];

        $like = new Like();
        $like->deleteLike($id_article,$id_like);
    }

    if(isset($_POST['createComment'])){

        Validator::validateCsrf();

        $id_article = (int) $_POST['id_article'];
        $id_user = (int) $_POST['id_user'];
        $content = trim($_POST['content']);

        $comment = new Comment();
        $comment->createComment($content, $id_article, $id_user);
    }

    if(isset($_POST['deleteComment'])){

        Validator::validateCsrf();

        $id_comment = (int) $_POST['id_comment'];

        $comment = new Comment();
        $comment->deleteComment($id_comment, $id_article);
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$contentErr = $_SESSION["contentErr"] ?? null;
$message = $_SESSION["message"] ?? null;
unset($_SESSION["contentErr"],$_SESSION["message"]);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article - SukiBlog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    
    <?php include_once "../../layouts/header.php";?>


    <div class="container mx-auto py-8 px-4">
            <?php 
            if($message){
                echo "
                    <div class='max-w-3xl mx-auto bg-green-600 rounded-lg my-5 py-5 ps-5'>
                        <p class='text-white font-bold'>$message</p>
                    </div>
                ";
            }
            ?>
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-xl">

            <article>
                <h1 class="text-4xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($article_infos["titre"]) ?></h1>
                <p class="text-sm text-gray-600 mb-4">Publié le 17 décembre 2024 par Auteur</p>
                <img class="w-full h-[700PX] object-cover rounded-lg mb-6" src="../.<?php echo htmlspecialchars($article_infos["img"]) ?>" alt="image article">
                <p class="text-lg text-gray-700 mb-6 break-words"><?php echo $article_infos["content"] ?></p>
            </article>

            <ul class="list-disc my-10">
                Categories
                <?php
                if ($categories->num_rows > 0) {
                    while ($row = $categories->fetch_assoc()) {
                        echo "
                            <li>{$row["category_name"]}</li>
                        ";
                    }
                }
                ?>
            </ul>


            <div class="flex justify-between items-center mb-6">
                <?php
                    if($userId){
                        if(!$is_liked){
                            echo "
                            <form method='POST' class='mb-6'>
                                <input type='hidden' name='csrf_token' value='". htmlspecialchars($_SESSION['csrf_token']) . "'>
                                <input type='hidden' value='". htmlspecialchars($article_infos["id_article"]) . "' name='id_article'>
                                <input type='hidden' value='$userId' name='id_user'>
                                <button name='createLike'>
                                    <svg class='w-1/12' fill='black' version='1.1' id='Capa_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' 
                                        viewBox='0 0 471.701 471.701' xml:space='preserve'>
                                        <g>
                                            <path d='M433.601,67.001c-24.7-24.7-57.4-38.2-92.3-38.2s-67.7,13.6-92.4,38.3l-12.9,12.9l-13.1-13.1
                                                c-24.7-24.7-57.6-38.4-92.5-38.4c-34.8,0-67.6,13.6-92.2,38.2c-24.7,24.7-38.3,57.5-38.2,92.4c0,34.9,13.7,67.6,38.4,92.3
                                                l187.8,187.8c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-3.9l188.2-187.5c24.7-24.7,38.3-57.5,38.3-92.4
                                                C471.801,124.501,458.301,91.701,433.601,67.001z M414.401,232.701l-178.7,178l-178.3-178.3c-19.6-19.6-30.4-45.6-30.4-73.3
                                                s10.7-53.7,30.3-73.2c19.5-19.5,45.5-30.3,73.1-30.3c27.7,0,53.8,10.8,73.4,30.4l22.6,22.6c5.3,5.3,13.8,5.3,19.1,0l22.4-22.4
                                                c19.6-19.6,45.7-30.4,73.3-30.4c27.6,0,53.6,10.8,73.2,30.3c19.6,19.6,30.3,45.6,30.3,73.3
                                                C444.801,187.101,434.001,213.101,414.401,232.701z'/>
                                        </g>
                                    </svg>
                                </button>
                            </form>
                        ";
                        }else{
                            echo "
                            <form method='POST' class='mb-6'>
                                <input type='hidden' name='csrf_token' value='". htmlspecialchars($_SESSION['csrf_token']) . "'>
                                <input type='hidden' value='". htmlspecialchars($article_infos["id_article"]) . "' name='id_article'>
                                <input type='hidden' value='$id_like' name='id_like'>
                                <button name='deleteLike'>
                                    <svg class='w-1/12' fill='red' version='1.1' id='Capa_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' 
                                        viewBox='0 0 471.701 471.701' xml:space='preserve'>
                                        <g>
                                            <path d='M433.601,67.001c-24.7-24.7-57.4-38.2-92.3-38.2s-67.7,13.6-92.4,38.3l-12.9,12.9l-13.1-13.1
                                                c-24.7-24.7-57.6-38.4-92.5-38.4c-34.8,0-67.6,13.6-92.2,38.2c-24.7,24.7-38.3,57.5-38.2,92.4c0,34.9,13.7,67.6,38.4,92.3
                                                l187.8,187.8c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-3.9l188.2-187.5c24.7-24.7,38.3-57.5,38.3-92.4
                                                C471.801,124.501,458.301,91.701,433.601,67.001z M414.401,232.701l-178.7,178l-178.3-178.3c-19.6-19.6-30.4-45.6-30.4-73.3
                                                s10.7-53.7,30.3-73.2c19.5-19.5,45.5-30.3,73.1-30.3c27.7,0,53.8,10.8,73.4,30.4l22.6,22.6c5.3,5.3,13.8,5.3,19.1,0l22.4-22.4
                                                c19.6-19.6,45.7-30.4,73.3-30.4c27.6,0,53.6,10.8,73.2,30.3c19.6,19.6,30.3,45.6,30.3,73.3
                                                C444.801,187.101,434.001,213.101,414.401,232.701z'/>
                                        </g>
                                    </svg>
                                </button>
                            </form>
                            ";
                        }
                        
                    } 
                ?>
                <p class="text-gray-600"><?php echo $likesCount ?> personnes aiment cet article</p>
            </div>


            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Commentaires</h2>
                
                <?php
                    if($userId){
                        echo "
                            <form method='POST' class='mb-6'>
                                <input type='hidden' name='csrf_token' value='". htmlspecialchars($_SESSION['csrf_token']) . "'>
                                <input type='hidden' value='". htmlspecialchars($article_infos["id_article"]) . "' name='id_article'>
                                <input type='hidden' value='$userId' name='id_user'>
                                <textarea class='w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out' rows='4' placeholder='Écrivez votre commentaire...' name='content' ></textarea>
                                ".
                                 ($contentErr ? "<p class='text-red-600 text-sm lg:text-[15px]'>$contentErr</p>" : null)
                                ."
                                <button name='createComment' type='submit' class='w-full bg-blue-500 text-white py-2 mt-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 ease-in-out'>
                                    Publier le commentaire
                                </button>
                            </form>
                        ";
                    }else{
                        echo "
                            <a href='../Auth/login.php' class='block mb-6 w-full bg-blue-500 text-white text-center py-2 mt-4 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out'>
                                Login To Comment
                            </a>
                        ";
                    }
                ?>

                <?php
                if ($comments->num_rows > 0) {
                    while ($row = $comments->fetch_assoc()) {
                        echo "
                            <div class='border-t border-gray-200 pt-4 flex justify-between'>
                                <div class='mb-6 w-10/12'>
                                    <p class='font-semibold text-gray-800'>" . htmlspecialchars($row["nom"]) . "</p>
                                    <p class='text-gray-600'>{$row["content"]}</p>
                                </div>
                                ".
                                    (
                                        $userId ? (
                                            $userId == $row["id_user"] ? "
                                                <div class='mb-6 w-1/12'>
                                                    <form method='POST'>
                                                        <input type='hidden' name='csrf_token' value='". htmlspecialchars($_SESSION['csrf_token']) . "'>
                                                        <input type='hidden' value='". htmlspecialchars($article_infos["id_article"]) . "' name='id_article'>
                                                        <input type='hidden' name='id_comment' value='{$row["id_comment"]}'>
                                                        <button name='deleteComment' type='submit' class='text-blue-900 h-5 cursor-pointer'>
                                                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' class='w-5 h-5'>
                                                                <path fill='currentColor' d='M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z'/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            " : null
                                        ) : null

                                    )
                                ."
                            </div>
                        ";
                    }
                }else{
                    echo "
                            <div class='border-t border-gray-200 pt-4'>
                                <div class='mb-6'>
                                    <p class='text-gray-600'>No comments</p>
                                </div>
                            </div>
                        ";
                }
                ?>
            </div>

        </div>
    </div>


    <!-- Footer -->
    <footer class="bg-blue-600 text-white p-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 KSBlog. Tous droits réservés.</p>
        </div>
    </footer>

</body>

</html>