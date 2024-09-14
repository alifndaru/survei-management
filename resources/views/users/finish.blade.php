<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Completion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f2f5;
        }

        .container {
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            box-shadow: 0 5px #fbfafa;
        }

        .btn:hover {
            background-color: #0056b3
        }

        .btn:active {
            background-color: #0056b3;
            box-shadow: 0 2px #666;
            transform: translateY(4px);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Thank You!</h1>
        <p>Your submission has been received.</p>
        <a href="{{ route('users.index') }}" class="btn">Back to Home</a>
    </div>
</body>

</html>
