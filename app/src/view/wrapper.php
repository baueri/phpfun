<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimalist Home</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<header>
    <h1>Welcome to [Your Site Name]</h1>
    <p>Your tagline or a short description goes here.</p>
</header>

<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/user/create">Create a user</a></li>
        <li><a href="/user/list">Users</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
</nav>

<main>
    <?= $content ?>
</main>

<footer>
    <p>&copy; 2024 [Your Site Name]. All rights reserved.</p>
</footer>
</body>
</html>
