<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Japanese Recipes Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100">
    <div id="loading-screen"></div>
    <!-- Hero Section -->
    <div id="content" class="myHidden">
        <header class="bg-cover bg-center h-screen">
            
            <?php include_once "./layouts/header.php";?>

            <div class="h-full flex items-center justify-center text-center text-white relative">
                <video class="absolute z-10 w-full h-full object-cover" autoplay loop muted>
                    <source src="./images/Aesthetic.webm" type="video/webm">
                    Your browser does not support HTML video.
                </video>
                <div class="absolute z-20">
                    <h1 class="text-5xl md:text-7xl font-bold mb-4">Explore Authentic Japanese Recipes</h1>
                    <p class="text-lg md:text-xl mb-6">Discover the art of Japanese cuisine and create delicious dishes at home.</p>
                    <a href="./pages/Articles/afficherArticles.php" class="bg-blue-500 hover:bg-blue-700 text-white py-3 px-6 rounded-lg text-lg font-semibold transition duration-300">Browse Recipes</a>
                </div>
            </div>
        </header>

        <section id="recipes" class="my-20 py-20 bg-gray-100">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold text-gray-800 text-center mb-8">Popular Recipes</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                        <img src="./images/articles/Taiyaki.jpg" alt="Sushi" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-blue-500 mb-2">Classic Sushi Rolls</h3>
                            <p class="text-gray-600 mb-4">Learn to craft authentic sushi rolls with fresh ingblueients.</p>
                            <a href="#" class="text-blue-500 hover:underline font-semibold">Read More</a>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                        <img src="./images/articles/Kimchi Noodles.jpg" alt="Ramen" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-blue-500 mb-2">Traditional Ramen</h3>
                            <p class="text-gray-600 mb-4">Enjoy the rich flavors of homemade Japanese ramen.</p>
                            <a href="#" class="text-blue-500 hover:underline font-semibold">Read More</a>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                        <img src="./images/articles/Dango.jpg" alt="Tempura" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-blue-500 mb-2">Crispy Tempura</h3>
                            <p class="text-gray-600 mb-4">Master the art of light and crispy tempura at home.</p>
                            <a href="#" class="text-blue-500 hover:underline font-semibold">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-blue-700 text-white py-6">
            <div class="container mx-auto text-center">
                <p>&copy; 2024 Japanese Recipes Blog. All rights reserved to Kawtar SHAIMI.</p>
            </div>
        </footer>
    </div>
    <script src="script.js"></script>
</body>
</html>