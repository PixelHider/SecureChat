<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Access Code</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: #fff;
            padding: 40px 60px;
            border-radius: 10px;
            box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            font-size: 2rem;
            color: #4a4a4a;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
            display: block;
            text-align: center;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 2px solid #ddd;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #667eea;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #667eea;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #5a66d9;
        }

        .error-messages {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-messages ul {
            list-style-type: none;
            padding-left: 0;
        }

        .error-messages li {
            margin-bottom: 10px;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Verify Your Access Code</h1>

        @if($errors->any())
            <div class="error-messages text-center">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/verify-code" method="POST">
            @csrf
            <label for="access_code">Enter Access Code:</label>
            <input type="text" name="access_code" id="access_code" required>

            <button type="submit">Verify Code</button>
        </form>

        <div class="footer-text">
            <p>Powered by SecureChat &copy; 2025</p>
        </div>
    </div>

</body>
</html>
