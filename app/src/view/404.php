<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body, html {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f2f2f2;
            color: #333;
            font-size: 16px;
            overflow: hidden;
        }

        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
        }

        .error-code {
            font-size: 100px;
            font-weight: 700;
            color: #333;
        }

        .message {
            font-size: 24px;
            margin-top: 10px;
            color: #666;
        }

        .home-link {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            color: #fff;
            background: #333;
            border-radius: 25px;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .home-link:hover {
            background: #555;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="error-code">404</div>
    <p class="message">Oops! The page you are looking for does not exist.</p>
    <a href="/" class="home-link">Go Back Home</a>
</div>
</body>
</html>
