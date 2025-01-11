<?php 

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use Src\Models\Article;
use Src\Models\Category;
use Src\Models\Validator;

Validator::validateAdmin();

$id_article = (int) $_GET['id'];

$articles = new Article();
$article_infos = $articles->getArticle($id_article);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['updateArticle'])) {

        Validator::validateCsrf();

        $id_article = (int) $_POST['id_article'];
        $titre = trim($_POST['titre']);
        $content = trim($_POST['content']);
        $img = $_FILES['img'];
        $categories = $_POST['categories'];
        $userRole = $_COOKIE['user_role'] ?? ($_SESSION['user_role'] ?? null);

        $article = new Article();
        $article->updateArticle($id_article, $titre, $content, $img, $article_infos['img'], $categories, $userRole);
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$categories = new Category();
$categories = $categories->getCategories();

$titleErr = $_SESSION["titleErr"] ?? null;
$contentErr = $_SESSION["contentErr"] ?? null;
$imgErr = $_SESSION["imgErr"] ?? null;
$categoriesErr = $_SESSION["categoriesErr"] ?? null;
unset($_SESSION["titleErr"],$_SESSION["contentErr"],$_SESSION["imgErr"],$_SESSION["categoriesErr"]);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Article</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js"></script>
</head>

<body class="bg-gray-50 overflow-x-hidden">

    <div class="flex min-h-screen">
        <?php include_once "../../../layouts/sidebar.php";?>

        <div class="flex-1 p-6">
            <?php include_once "../../../layouts/statics.php";?>

            <div class="mt-6 flex justify-center items-center">
               <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-xl">
                    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Modifier un Article</h1>
                    <form method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        <input type="hidden" name="id_article" value="<?php if ($article_infos) echo $article_infos['id_article']; ?>">

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre de l'article</label>
                            <input type="text" id="title" name="titre" placeholder="Entrez le titre" required
                                class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" value="<?php if ($article_infos) echo $article_infos['titre']; ?>">
                            <?php if($titleErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$titleErr</p><br>" ?>
                        </div>
                    
                        <div class="mb-4">
                            <label for="editor" class="block text-sm font-medium text-gray-700">Contenu de l'article</label>
                            <textarea id="editor" name="content" rows="6" placeholder="Entrez le contenu ici" required
                                class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"><?php if ($article_infos) echo $article_infos['content']; ?></textarea>
                            <?php if($contentErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$contentErr</p><br>" ?>
                        </div>

                        <div class="mb-6">
                            <label for="cur_image" class="block text-sm font-medium text-gray-700">Current Image</label>
                            <img src="<?php if ($article_infos) echo "../../.". $article_infos['img']; ?>" id="cur_image" class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        
                        <div class="mb-6">
                            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" accept=".png, .jpg, .jpeg, .webp" id="image" name="img" placeholder="Entrez le chemin de l'image"
                                class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <?php if($imgErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$imgErr</p><br>" ?>
                        </div>
            
                        <div class="mb-6">
                            <label for="categories" class="block text-sm font-medium text-gray-700">Categories</label>
                            <select multiple id="categories" name="categories[]" class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <option>-- Choisire votre categories --</option>
                                <?php
                                if ($categories->num_rows > 0) {
                                    while ($row = $categories->fetch_assoc()) {
                                        echo "
                                            <option value='{$row["id_category"]}'>{$row["category_name"]}</option>
                                        ";
                                    }
                                }
                                ?>
                            </select>
                            <?php if($categoriesErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$categoriesErr</p><br>" ?>
                        </div>
                        
                        <div class="text-center">
                            <button name="updateArticle" type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white font-medium text-lg rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Modifier l'Article
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="bg-blue-600 text-white p-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 KSBlog. Tous droits réservés.</p>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            $('#editor').trumbowyg();
        });
    </script>

</body>

</html>
