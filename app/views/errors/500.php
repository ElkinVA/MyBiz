<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ошибка сервера - MyBiz</title>
    <style>
        .error-container {
            max-width: 600px;
            margin: 100px auto;
            text-align: center;
            padding: 40px;
            font-family: Arial, sans-serif;
        }
        .error-code {
            font-size: 72px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .error-message {
            font-size: 24px;
            margin-bottom: 30px;
            color: #333;
        }
        .home-link {
            display: inline-block;
            padding: 12px 24px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .home-link:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <div class="error-message">Внутренняя ошибка сервера</div>
        <p>Произошла непредвиденная ошибка. Мы уже работаем над ее устранением.</p>
        <a href="/" class="home-link">Вернуться на главную</a>
    </div>
</body>
</html>