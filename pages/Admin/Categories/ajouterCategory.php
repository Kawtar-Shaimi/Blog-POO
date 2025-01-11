<?php 

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use Src\Models\Category;
use Src\Models\Validator;

Validator::validateAdmin();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['createCategory'])){

        Validator::validateCsrf();

        $category_name = trim($_POST['category_name']);

        $category = new Category();
        $category->createCategory($category_name);
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$categoryNameErr = $_SESSION["categoryNameErr"] ?? null;
unset($_SESSION["categoryNameErr"]);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Article</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 overflow-x-hidden">

    <div class="flex min-h-screen">
        <?php include_once "../../../layouts/sidebar.php";?>

        <div class="flex-1 p-6">
            <?php include_once "../../../layouts/statics.php";?>

            <div class="mt-6 flex justify-center items-center">
               <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-xl">
                    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Ajouter un category</h1>
                    <form method="POST">

                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                        <div class="mb-4">
                            <label for="category_name" class="block text-sm font-medium text-gray-700">Nom de la category</label>
                            <input type="text" id="category_name" name="category_name" placeholder="Entrez le nom" required
                                class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <?php if($categoryNameErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$categoryNameErr</p><br>" ?>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="createCategory"
                                class="px-6 py-3 bg-blue-600 text-white font-medium text-lg rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Ajouter la category
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

</body>

</html>
