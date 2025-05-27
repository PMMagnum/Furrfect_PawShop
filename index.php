<?php
session_start();
require 'config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    // Using PDO or MySQLi based on what's defined in config.php
    if (isset($pdo)) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
    }
    if ($product) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Furrfect Pawshop</title>
    <link rel="stylesheet" href="index.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', Arial, sans-serif;
        }

        body {
            background: #F5F5DC;
            color: #5C4033;
            line-height: 1.6;
            width: 100vw;
            overflow-x: hidden;
        }

        /* Hero Section - Full Screen with Slideshow */
        .hero {
            position: relative;
            text-align: center;
            padding: 0;
            min-height: 100vh;
            width: 100vw;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .slideshow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slide.active {
            opacity: 1;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(245, 245, 220, 0.3);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 2rem;
        }

        .hero h1 {
            font-size: 4rem;
            color: #5C4033;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .hero p {
            font-size: 1.5rem;
            color: #5C4033;
            margin-bottom: 2.5rem;
            font-weight: 300;
        }

        .book-now {
            background: #D2B48C;
            color: white;
            padding: 1rem 3rem;
            text-decoration: none;
            border-radius: 25px;
            font-size: 1.3rem;
            transition: background 0.3s, transform 0.2s;
        }

        .book-now:hover {
            background: #A67B5B;
            transform: scale(1.05);
        }

        /* Best Sellers Section */
        .best-sellers {
            padding: 5rem 0;
            text-align: center;
            background: #EAD9C7;
            width: 100vw;
        }

        .best-sellers h2 {
            font-size: 3rem;
            color: #5C4033;
            margin-bottom: 3rem;
            font-weight: 600;
        }

        .product-container {
            display: flex;
            overflow-x: auto;
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            scroll-behavior: smooth;
            scrollbar-width: thin;
            scrollbar-color: #D2B48C #F5F5DC;
        }

        .product-container::-webkit-scrollbar {
            height: 8px;
        }

        .product-container::-webkit-scrollbar-thumb {
            background: #D2B48C;
            border-radius: 4px;
        }

        .product-container::-webkit-scrollbar-track {
            background: #F5F5DC;
        }

        .product-card {
            background: #FFF5E1;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            min-width: 200px;
            max-width: 220px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 0.8rem;
        }

        .product-card h3 {
            font-size: 1.3rem;
            color: #5C4033;
            margin-bottom: 0.4rem;
            font-weight: 600;
        }

        .product-card p {
            font-size: 1rem;
            color: #7A5C47;
            margin-bottom: 0.8rem;
            font-weight: 400;
        }

        .buy-now {
            background: #D2B48C;
            color: white;
            padding: 0.6rem 1.5rem;
            text-decoration: none;
            border-radius: 20px;
            font-size: 1rem;
            transition: background 0.3s, transform 0.2s;
        }

        .buy-now:hover {
            background: #A67B5B;
            transform: scale(1.05);
        }

        /* Promotions Section */
        .promotions {
            padding: 5rem 0;
            text-align: center;
            background: #F5F5DC;
            width: 100vw;
        }

        .promotions h2 {
            font-size: 3rem;
            color: #5C4033;
            margin-bottom: 3rem;
            font-weight: 600;
        }

        .promo-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 150px);
            gap: 1rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .promo-card {
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .promo-card:hover {
            transform: scale(1.02);
        }

        .promo-card:nth-child(1) {
            grid-row: 1 / 3;
            grid-column: 1 / 2;
        }

        .promo-card:nth-child(2) {
            grid-row: 1 / 2;
            grid-column: 2 / 3;
        }

        .promo-card:nth-child(3) {
            grid-row: 2 / 3;
            grid-column: 2 / 3;
        }

        .promo-card:nth-child(4) {
            grid-row: 1 / 2;
            grid-column: 3 / 4;
        }

        .promo-card:nth-child(5) {
            grid-row: 2 / 3;
            grid-column: 3 / 4;
        }

        .promo-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Login Popup */
        .login-popup {
            display: none;
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100%;
            background: #FFF5E1;
            box-shadow: -2px 0 10px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: right 0.3s ease;
            padding: 2rem;
            overflow-y: auto;
        }

        .login-popup.active {
            right: 0;
        }

        .login-popup h2 {
            font-size: 2rem;
            color: #5C4033;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .login-popup .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #5C4033;
        }

        .login-popup form {
            display: flex;
            flex-direction: column;
        }

        .login-popup label {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #7A5C47;
            font-weight: 400;
        }

        .login-popup input {
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #D2B48C;
            border-radius: 5px;
            font-size: 1rem;
        }

        .login-popup button {
            background: #D2B48C;
            color: white;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .login-popup button:hover {
            background: #A67B5B;
            transform: scale(1.05);
        }

        /* Dedication Section */
        .dedication {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            padding: 3rem 0;
            background: #F5E8D3;
            width: 100vw;
            max-width: 1200px;
            margin: 0 auto;
            gap: 2rem;
            position: relative;
            overflow: hidden;
        }

        .dedication-text {
            flex: 1;
            padding: 1.5rem;
            min-width: 300px;
            max-width: 500px;
            text-align: left;
            z-index: 2;
        }

        .dedication-text h2 {
            font-size: 2.5rem;
            color: #5C4033;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .dedication-text p {
            font-size: 1.1rem;
            color: #7A5C47;
            font-weight: 300;
            line-height: 1.8;
        }

        .dedication-gallery {
            position: relative;
            flex: 1;
            min-width: 300px;
            min-height: 300px;
            background: url('images/collage4.jpg') no-repeat center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .photo-frame {
            position: absolute;
            border: 10px solid #D9C2A8;
            background: #333;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .photo-frame img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .frame1 {
            top: 20%;
            left: 10%;
            transform: rotate(-5deg);
            z-index: 3;
        }

        .frame2 {
            top: 30%;
            left: 40%;
            transform: rotate(5deg);
            z-index: 2;
        }

        .frame3 {
            top: 50%;
            left: 20%;
            transform: rotate(-10deg);
            z-index: 1;
        }

        /* Ways to Shop Section */
        .ways-to-shop {
            padding: 5rem 0;
            text-align: center;
            background: #F5F5DC;
            width: 100vw;
        }

        .ways-to-shop h2 {
            font-size: 3rem;
            color: #5C4033;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .ways-to-shop .subtitle {
            font-size: 1.2rem;
            color: #7A5C47;
            margin-bottom: 2rem;
            font-weight: 300;
        }

        .ways-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .way-card {
            background: #FFF5E1;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .way-card img {
            width: 100px;
            height: 100px;
            margin-bottom: 1rem;
        }

        .way-card h4 {
            font-size: 1.5rem;
            color: #5C4033;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .way-card p {
            font-size: 1rem;
            color: #7A5C47;
            font-weight: 300;
        }

        /* Footer */
        .main-footer {
            background: #EAD9C7;
            padding: 2rem 0;
            text-align: center;
            width: 100vw;
        }

        .footer-logo {
            font-size: 2rem;
            color: #5C4033;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-contact-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .footer-contact {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #7A5C47;
            font-size: 1rem;
        }

        .footer-contact i {
            color: #D2B48C;
        }

        .footer-bottom-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            padding-top: 1rem;
        }

        .footer-nav {
            list-style: none;
            display: flex;
            gap: 1rem;
        }

        .footer-nav li a {
            color: #5C4033;
            text-decoration: none;
            font-size: 1rem;
        }

        .footer-nav li a:hover {
            color: #A67B5B;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
        }

        .footer-social a {
            color: #5C4033;
            font-size: 1.5rem;
        }

        .footer-social a:hover {
            color: #A67B5B;
        }

        .footer-copy {
            font-size: 0.9rem;
            color: #7A5C47;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.2rem;
            }

            .book-now {
                padding: 0.8rem 2rem;
                font-size: 1.1rem;
            }

            .best-sellers h2, .promotions h2, .dedication-text h2, .ways-to-shop h2 {
                font-size: 2rem;
            }

            .product-container, .promo-grid, .ways-grid {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
                gap: 1rem;
            }

            .product-card {
                min-width: 180px;
                max-width: 200px;
            }

            .promo-card {
                grid-row: auto !important;
                grid-column: auto !important;
                height: 150px;
            }

            .dedication {
                flex-direction: column;
                text-align: center;
                padding: 2rem 0;
            }

            .dedication-text {
                text-align: center;
                padding: 1rem;
            }

            .dedication-gallery {
                min-height: 200px;
                background-size: contain;
            }

            .photo-frame img {
                width: 150px;
                height: 150px;
            }

            .frame1 {
                top: 10%;
                left: 5%;
                transform: rotate(0deg);
            }

            .frame2 {
                top: 40%;
                left: 5%;
                transform: rotate(0deg);
            }

            .frame3 {
                top: 70%;
                left: 5%;
                transform: rotate(0deg);
            }

            .footer-contact-row {
                flex-direction: column;
                gap: 1rem;
            }
        }

        /* Top Deals & Trends Styling */
        .top-deal-item {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .top-deal-item:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 8px 24px rgba(124,94,182,0.18);
            background: #FFF5E1;
        }
    </style>
</head>
<body>
    <!-- Hero Section with Slideshow -->
    <section class="hero">
        <div class="slideshow">
            <div class="slide active">
                <img src="images/eppy.jpg" alt="Pet Slide 1">
            </div>
            <div class="slide">
                <img src="images/f8aa0169b6a8b4c31d31e9a1e55d0b8b.jpg" alt="Pet Slide 2">
            </div>
            <div class="slide">
                <img src="images/Pet Shop Banner.png" alt="Pet Slide 3">
            </div>
            <div class="slide">
                <img src="images/him.jpg" alt="Banner 1">
            </div>
            <div class="slide">
                <img src="images/landing.jpg" alt="Banner 2">
            </div>
            <div class="slide">
                <img src="images/warmth.jpg" alt="Banner 3">
            </div>
        </div>
        <div class="hero-content">
            <h1>Best Pals for Your Paw Pals</h1>
            <p>Loving Care, Nurturing Hearts – Offering Tailored Services to Ensure the Happiness, Health, and Well-Being of Your Beloved Furry Companions.</p>
            <a href="login.php" class="book-now btn btn-lg">Shop Now</a>
        </div>
    </section>

    <!-- Best Sellers Section -->
    <section class="best-sellers">
        <h2>Our Best Sellers</h2>
        <div class="product-container">
            <div class="product-card">
                <img src="images/best1-removebg-preview.png" alt="Premium Dog Food">
                <h3>Premium Dog Food</h3>
                <p>$29.99</p>
                <a href="login.php" class="buy-now btn">Buy Now</a>
            </div>
            <div class="product-card">
                <img src="images/best2.webp" alt="Cat Toy Set">
                <h3>Cat Toy Set</h3>
                <p>$14.99</p>
                <a href="login.php" class="buy-now btn">Buy Now</a>
            </div>
            <div class="product-card">
                <img src="images/best4.png" alt="Pet Bed">
                <h3>Cozy Pet Bed</h3>
                <p>$39.99</p>
                <a href="login.php" class="buy-now btn">Buy Now</a>
            </div>
            <div class="product-card">
                <img src="images/best3-removebg-preview.png" alt="Pet Grooming Kit">
                <h3>Pet Grooming Kit</h3>
                <p>$24.99</p>
                <a href="login.php" class="buy-now btn">Buy Now</a>
            </div>
        </div>
    </section>

    <!-- Promotions Section -->
    <section class="promotions">
        <h2>Explore Our Promotions</h2>
        <div class="promo-grid">
            <div class="promo-card">
                <a href="login.php"><img src="images/promo1.png" alt="Pet Food"></a>
            </div>
            <div class="promo-card">
                <a href="login.php"><img src="images/promo2.png" alt="Dog"></a>
            </div>
            <div class="promo-card">
                <a href="login.php"><img src="images/promo3.png" alt="Parrot"></a>
            </div>
            <div class="promo-card">
                <a href="login.php"><img src="images/promo4.png" alt="Dog"></a>
            </div>
            <div class="promo-card">
                <a href="login.php"><img src="images/promo5.png" alt="Cat Food"></a>
            </div>
        </div>
    </section>

    <!-- Dedication Section -->
    <section class="dedication">
        <div class="dedication-text">
            <h2>ABOUT US</h2>
            <p>At Furrfect Pawshop, we’re passionate about pets. We provide loving care, nurturing hearts, to ensure your furry friends are happy, healthy, and thriving. Trust us to be your partner in pet care!</p>
        </div>
        <div class="dedication-gallery">
            <div class="photo-frame frame1">
                <img src="images/collage3.jpg" alt="Pet Image 1">
            </div>
            <div class="photo-frame frame2">
                <img src="images/collage2.jpg" alt="Pet Image 2">
            </div>
            <div class="photo-frame frame3">
                <img src="images/featured1.jpg" alt="Pet Image 3">
            </div>
        </div>
    </section>

    <!-- Login Popup -->
    <div class="login-popup" id="loginPopup">
        <span class="close-btn" onclick="hideLoginPopup()">×</span>
        <h2>Sign In</h2>
        <form>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>

    <!-- Ways to Shop Section -->
    <section class="ways-to-shop">
        <div class="container">
            <h2>Ways to Shop</h2>
            <p class="subtitle">Convenient ways to ship & save to help you pet your best!</p>
            <div class="ways-grid">
                <div class="way-card">
                    <img src="images/curb.svg" alt="Curbside Pickup" />
                    <h4>2 HOURS OR LESS</h4>
                    <p>Curbside & Store Pickup<br>FREE pickup ready in 2 hours or less</p>
                </div>
                <div class="way-card">
                    <img src="images/Fandf.svg" alt="Same-Day Delivery" />
                    <h4>FREE & FAST</h4>
                    <p>Same-Day Delivery<br>Fast & FREE delivery when you need it</p>
                </div>
                <div class="way-card">
                    <img src="images/c.png" alt="Autoship" />
                    <h4>SAVE 35%</h4>
                    <p>Autoship<br>35% OFF 1st order + 5% OFF future orders</p>
                </div>
                <div class="way-card">
                    <img src="images/free-removebg-preview.png" alt="Free Shipping" />
                    <h4>FREE SHIPPING</h4>
                    <p>1-3 Day Shipping<br>FREE Shipping on select orders $49+</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-logo">Furrfect Pawshop</div>
        <div class="footer-contact-row">
            <div class="footer-contact">
                <i class="fa-solid fa-phone"></i>
                <span>0212 123 45 67</span>
            </div>
            <div class="footer-contact">
                <i class="fa-solid fa-comments"></i>
                <span>demo@furrfectpawshop.com</span>
            </div>
            <div class="footer-contact">
                <i class="fa-solid fa-location-dot"></i>
                <span>Cebu, Philippines</span>
            </div>
        </div>
        <hr>
        <div class="footer-bottom-row">
            <ul class="footer-nav">
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <div class="footer-social">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </div>
            <div class="footer-copy">
                © 2025 Furrfect Pawshop
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Slideshow functionality
        const slides = document.querySelectorAll('.slide');
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        showSlide(currentSlide);
        setInterval(nextSlide, 5000);

        // Login Popup functionality
        function showLoginPopup() {
            const popup = document.getElementById('loginPopup');
            popup.classList.add('active');
        }

        function hideLoginPopup() {
            const popup = document.getElementById('loginPopup');
            popup.classList.remove('active');
        }

        // Category filtering
        document.querySelectorAll('.category-link').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const cat = e.target.dataset.category;
                document.querySelectorAll('.product').forEach(prod => {
                    prod.style.display = (cat === 'all' || prod.dataset.category === cat) ? 'block' : 'none';
                });
            });
        });

        // Search filtering
        document.getElementById('search-input')?.addEventListener('input', e => {
            const val = e.target.value.toLowerCase();
            document.querySelectorAll('.product').forEach(prod => {
                const text = prod.textContent.toLowerCase();
                prod.style.display = text.includes(val) ? 'block' : 'none';
            });
        });

        // Top Deals & Trends click functionality
        document.querySelectorAll('.top-deal-item').forEach(item => {
            item.addEventListener('click', function() {
                const cat = this.dataset.category;
                document.querySelectorAll('.product').forEach(prod => {
                    prod.style.display = (prod.dataset.category === cat) ? 'block' : 'none';
                });
                document.querySelector('.product-list')?.scrollIntoView({ behavior: 'smooth' });
            });
            item.style.cursor = 'pointer';
        });
    </script>
</body>
</html>