<?php
session_start();
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Ecommerce</title>
    <style>
        .hero-section {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            min-height: 100vh;
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card-icon {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }
        .welcome-text {
            color: var(--text-primary);
            margin-bottom: 3rem;
        }
        .feature-icon {
            font-size: 2rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }
        .features {
            padding: 50px 0;
            background-color: var(--bg-secondary);
        }
    </style>
</head>
<body>

<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-md-8">
                <h1 class="display-4 welcome-text">Welcome to Our Ecommerce Platform</h1>
                <p class="lead mb-5" style="color: var(--text-secondary)">Choose how you want to proceed</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-md-5 mb-4">
                <div class="card h-100 text-center p-4" onclick="window.location.href='cont.php'">
                    <div class="card-body">
                        <i class="fas fa-user-shield card-icon"></i>
                        <h3 class="card-title">Admin Access</h3>
                        <p class="card-text" style="color: var(--text-secondary)">
                            Manage products, categories, and user accounts. 
                            Monitor sales and maintain the platform.
                        </p>
                        <button class="btn btn-primary mt-3">Login as Admin</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5 mb-4">
                <div class="card h-100 text-center p-4" onclick="window.location.href='front/index.php'">
                    <div class="card-body">
                        <i class="fas fa-shopping-bag card-icon"></i>
                        <h3 class="card-title">Shop Now</h3>
                        <p class="card-text" style="color: var(--text-secondary)">
                            Browse our collection of products, 
                            explore categories, and find great deals.
                        </p>
                        <button class="btn btn-success mt-3">Enter Store</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="features">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <i class="fas fa-truck feature-icon"></i>
                <h4>Fast Delivery</h4>
                <p style="color: var(--text-secondary)">Quick and reliable shipping to your doorstep</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-shield-alt feature-icon"></i>
                <h4>Secure Shopping</h4>
                <p style="color: var(--text-secondary)">Your transactions are safe with us</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-headset feature-icon"></i>
                <h4>24/7 Support</h4>
                <p style="color: var(--text-secondary)">Always here to help you</p>
            </div>
        </div>
    </div>
</section>

</body>
</html>
