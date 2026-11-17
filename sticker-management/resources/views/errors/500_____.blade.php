<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>500 Internal Server Error</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f8f9fa;
    color: #444;
}

.container {
    text-align: center;
    max-width: 500px;
    padding: 20px;
}

.icon-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
}

.five, .zero {
    font-size: 10rem;
    color: #e74c3c;
    font-weight: bold;
    position: relative;
    animation: pulse 1.5s infinite ease-in-out;
}

.zero {
    color: #ff6b6b;
}

/* Pulse animation */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
    }
}

p {
    font-size: 1.5rem;
    margin-bottom: 20px;
}

a.btn {
    padding: 10px 20px;
    background-color: #e74c3c;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-size: 1.2rem;
    transition: background-color 0.3s;
}

a.btn:hover {
    background-color: #c0392b;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="icon-container">
            <div class="five">5</div>
            <div class="zero">0</div>
            <div class="zero">0</div>
        </div>
        <p>Oops! Something went wrong on our end.</p>
    </div>
</body>
</html>
