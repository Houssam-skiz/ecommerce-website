<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom Theme CSS -->
    <link href="/proeco/assets/css/theme.css" rel="stylesheet">
    <style>
        .theme-container {
            display: flex;
            align-items: center;
            margin-left: auto;
            padding-right: 1rem;
        }
    </style>
</head>
<body>
<?php
// Initialize theme from cookie if exists
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
echo "<script>document.documentElement.setAttribute('data-theme', '$theme');</script>";
?>

<!-- Bootstrap JS and Theme JS will be included at the end of body -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/proeco/assets/js/theme.js"></script>
