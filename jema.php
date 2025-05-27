<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furrfect Pawshop</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="img\finallogo.png" alt="Furrfect Pawshop Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Products</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
            <div class="cart-login">
                <a href="#" class="cart-icon">Cart <span id="cart-count">0</span></a>
                <a href="#" class="login-btn">Login</a>
                <a href="#" class="signup-btn">Sign Up</a>
            </div>
        </div>
    </header>

    <section class="banner">
        <div class="banner-slide"><img src="img/warmth.jpg" alt="Banner 1"></div>
        <div class="banner-slide"><img src="img/banner2.jpg" alt="Banner 2"></div>
        <div class="banner-slide"><img src="img/banner3.jpg" alt="Banner 3"></div>
    </section>

    <section class="search-bar">
        <input type="text" placeholder="Search for products...">
    </section>

    <div class="shop-container">
        <aside class="category-sidebar">
            <h3>Categories</h3>
            <ul>
                <li><a href="#" class="category-link" data-category="all">All Products</a></li>
                <li><a href="#" class="category-link" data-category="Feline Feast">Feline Feast</a></li>
                <li><a href="#" class="category-link" data-category="Puppy Picks">Puppy Picks</a></li>
                <li><a href="#" class="category-link" data-category="Fur-tastic Grooming">Fur-tastic Grooming</a></li>
                <li><a href="#" class="category-link" data-category="Pet Essentials">Pet Essentials</a></li>
            </ul>
        </aside>

        <main class="product-list">
            <div class="product" data-category="Feline Feast">
                <img src="img/products/Feline Feast/Whiskas Adult x6 (1.1kg) - 1188 pesos.jpg" alt="Whiskas Adult">
                <h4>Whiskas Adult</h4>
                <p>₱1188</p>
                <button>Add to Cart</button>
            </div>
            <div class="product" data-category="Feline Feast">
                <img src="img/products/Feline Feast/Goodest Cat Tender Tuna x4 (400g) - 327 pesos.jpg" alt="Goodest Cat Tuna">
                <h4>Goodest Cat Tender Tuna</h4>
                <p>₱327</p>
                <button>Add to Cart</button>
            </div>
            <div class="product" data-category="Puppy Picks">
                <img src="img/products/Puppy Picks/Pedigree Puppy Chicken x2 (1.2kg) - 668 pesos.jpg" alt="Pedigree Puppy">
                <h4>Pedigree Puppy Chicken</h4>
                <p>₱668</p>
                <button>Add to Cart</button>
            </div>
            <div class="product" data-category="Puppy Picks">
                <img src="img/products/Puppy Picks/Cesar Adult Wet Dog Food 100g - 65 pesos.jpg" alt="Cesar Adult Wet Dog Food">
                <h4>Cesar Adult Wet Dog Food</h4>
                <p>₱65</p>
                <button>Add to Cart</button>
            </div>
            <div class="product" data-category="Fur-tastic Grooming">
                <img src="img/products/Fur-tastic Grooming/Pet Comb Brush - 199 pesos.jpg" alt="Pet Comb Brush">
                <h4>Pet Comb Brush</h4>
                <p>₱199</p>
                <button>Add to Cart</button>
            </div>
            <div class="product" data-category="Fur-tastic Grooming">
                <img src="img/products/Fur-tastic Grooming/Pet Shampoo 250ml - 180 pesos.jpg" alt="Pet Shampoo">
                <h4>Pet Shampoo 250ml</h4>
                <p>₱180</p>
                <button>Add to Cart</button>
            </div>
            <div class="product" data-category="Pet Essentials">
                <img src="img/products/Pet Essentials/Pet Food Bowl - 120 pesos.jpg" alt="Pet Food Bowl">
                <h4>Pet Food Bowl</h4>
                <p>₱120</p>
                <button>Add to Cart</button>
            </div>
            <div class="product" data-category="Pet Essentials">
                <img src="img/products/Pet Essentials/Pet Water Dispenser - 350 pesos.jpg" alt="Pet Water Dispenser">
                <h4>Pet Water Dispenser</h4>
                <p>₱350</p>
                <button>Add to Cart</button>
            </div>
            <div class="product" data-category="Feline Feast">
                <img src="img/products/Feline Feast/Meow Mix Original Choice 1.43kg - 499 pesos.jpg" alt="Meow Mix Original Choice">
                <h4>Meow Mix Original Choice</h4>
                <p>₱499</p>
                <button>Add to Cart</button>
            </div>
            <div class="product" data-category="Puppy Picks">
                <img src="img/products/Puppy Picks/Pedigree Dentastix 7pcs - 120 pesos.jpg" alt="Pedigree Dentastix">
                <h4>Pedigree Dentastix 7pcs</h4>
                <p>₱120</p>
                <button>Add to Cart</button>
            </div>
            <div class="product" data-category="Fur-tastic Grooming">
                <img src="img/products/Fur-tastic Grooming/Pet Nail Clipper - 99 pesos.jpg" alt="Pet Nail Clipper">
                <h4>Pet Nail Clipper</h4>
                <p>₱99</p>
                <button>Add to Cart</button>
            </div>
            <div class="product" data-category="Pet Essentials">
                <img src="img/products/Pet Essentials/Pet Carrier Bag - 899 pesos.jpg" alt="Pet Carrier Bag">
                <h4>Pet Carrier Bag</h4>
                <p>₱899</p>
                <button>Add to Cart</button>
            </div>
            <!-- Add more products as needed -->
        </main>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 Furrfect Pawshop. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Slideshow
        let slideIndex = 0;
        const slides = document.querySelectorAll('.banner-slide');
        setInterval(() => {
            slides.forEach((slide, index) => {
                slide.style.opacity = index === slideIndex ? '1' : '0';
            });
            slideIndex = (slideIndex + 1) % slides.length;
        }, 4000);

        // Category filter
        document.querySelectorAll('.category-link').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const category = e.target.dataset.category;
                document.querySelectorAll('.product').forEach(product => {
                    if (category === 'all' || product.dataset.category === category) {
                        product.style.display = 'block';
                    } else {
                        product.style.display = 'none';
                    }
                });
            });
        });

        // Search filter
        document.querySelector('.search-bar input').addEventListener('input', e => {
            const searchValue = e.target.value.toLowerCase();
            document.querySelectorAll('.product').forEach(product => {
                const text = product.textContent.toLowerCase();
                product.style.display = text.includes(searchValue) ? 'block' : 'none';
            });
        });
    </script>
</body>

</html>
