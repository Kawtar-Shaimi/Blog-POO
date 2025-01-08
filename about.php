<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - SukiBlog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-blue-50">
    <div id="loading-screen"></div>
    <div id="content" class="myHidden">
        <!-- Header -->
        <?php include_once "./layouts/header.php";?>

        <!-- About Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto">
                <h1 class="text-4xl font-bold text-blue-600 text-center mb-8">About Us</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <!-- Text Content -->
                    <div class="text-gray-700 text-lg leading-relaxed p-6 bg-blue-50 rounded-lg shadow-md">
                        <p class="mb-6">Welcome to SukiBlog, your go-to source for authentic Japanese recipes. Our mission is to bring the rich culinary traditions of Japan to your kitchen, making it easy for anyone to enjoy the flavors of sushi, ramen, tempura, and much more.</p>
                        <p class="mb-6">We believe that food is more than just sustenance; it is a way to connect with culture, history, and loved ones. At SukiBlog, we are passionate about sharing not just recipes, but also the stories and techniques that make Japanese cuisine unique.</p>
                        <p class="mb-6">Whether you're an experienced chef or a curious beginner, our step-by-step guides and tips will help you create dishes that are both delicious and visually stunning. Join us on this culinary journey and discover the art of Japanese cooking.</p>
                        <p class="mb-6">Thank you for being a part of our community. We hope you enjoy your time on SukiBlog and find inspiration for your next meal!</p>
                    </div>

                    <!-- Image -->
                    <div>
                        <img src="./images/japan (2).jpg" alt="Japanese Food" class="rounded-lg shadow-lg">
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Journey Section -->
        <section class="py-16 bg-blue-100">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold text-blue-600 text-center mb-8">Our Journey</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Milestone 1 -->
                    <div class="text-center p-6 bg-white rounded-lg shadow-md">
                        <img src="./images/staff.jpg" alt="Our Chefs" class="rounded-lg mb-4 shadow-md">
                        <h3 class="text-2xl font-bold text-blue-500 mb-2">Expert Chefs</h3>
                        <p class="text-gray-600">Our recipes are crafted with the expertise of seasoned Japanese chefs.</p>
                    </div>

                    <!-- Milestone 2 -->
                    <div class="text-center p-6 bg-white rounded-lg shadow-md">
                        <img src="./images/suchiabout.jpg" alt="Sushi Art" class="rounded-lg mb-4 shadow-md">
                        <h3 class="text-2xl font-bold text-blue-500 mb-2">Authentic Recipes</h3>
                        <p class="text-gray-600">We bring you recipes that capture the essence of Japanese cuisine.</p>
                    </div>

                    <!-- Milestone 3 -->
                    <div class="text-center p-6 bg-white rounded-lg shadow-md">
                        <img src="./images/women.jpg" alt="Kitchen Stories" class="rounded-lg mb-4 shadow-md">
                        <h3 class="text-2xl font-bold text-blue-500 mb-2">Inspiring Stories</h3>
                        <p class="text-gray-600">Discover the stories behind the dishes and the culture they represent.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-blue-600 text-white py-6">
            <div class="container mx-auto text-center">
                <p>&copy; 2024 SukiBlog. All rights reserved to Kawtar SHAIMI.</p>
            </div>
        </footer>
    </div>
    <script src="script.js"></script>
</body>
</html>
