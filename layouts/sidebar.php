<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Src\Models\Auth;

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['logout'])){
        $auth = new Auth();
        $auth->logout();
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="sidebar w-64 bg-blue-600 text-white p-6">
    <h2 class="text-2xl font-semibold mb-6">Admin Dashboard</h2>
    <ul>
        <li><a href="http://localhost/Blog_POO/pages/Admin/Users/" class="block py-2 px-4 hover:bg-gray-700 rounded-lg">Utilisateurs</a></li>
        <li><a href="http://localhost/Blog_POO/pages/Admin/Articles/" class="block py-2 px-4 hover:bg-gray-700 rounded-lg">Articles</a></li>
        <li><a href="http://localhost/Blog_POO/pages/Admin/Categories/" class="block py-2 px-4 hover:bg-gray-700 rounded-lg">Categories</a></li>
        <li><a href="http://localhost/Blog_POO/pages/Admin/Comments/" class="block py-2 px-4 hover:bg-gray-700 rounded-lg">Comments</a></li>
        <li>
            <form class="w-full" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"] ?>">
                <button name="logout" class="w-full text-left block py-2 px-4 hover:bg-gray-700 rounded-lg">Logout</button>
            </form>
        </li>
    </ul>
</div>