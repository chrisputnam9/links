<?php
    $response_code = http_response_code();

    // Always 404 at best
    if ($response_code == 200)
    {
        http_response_code(404);
    }

    $url = $_SERVER['REQUEST_SCHEME'] . '://' .
        $_SERVER['HTTP_HOST'] . 
        $_SERVER['REQUEST_URI'];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>4o4</title>
	<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href=/favicon"/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#00aba9">
	<meta name="msapplication-TileColor" content="#00aba9">
	<meta name="theme-color" content="#b4abd4">
    <style type='text/css'>
        h1,p {
            font-family: 'sans-serif';
        }
        .container {
            display: block;
            box-sizing: border-box;
            width: 100%;
            max-width: 500px;
            padding: 20px;
            margin: 50px auto 0;
            text-align: center;
            border: 2px solid #eee;
        }
        input {
            font-family: 'courier';
            box-sizing: border-box;
            width: 100%;
            padding: 10px;
            font-size: 1.2em;
            background: #eee;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Hmmm...</h1>
        <p>That doesn't seem right... could this link have a typo?</p>
        <input type='text' readonly='readonly' value='<?php echo $url; ?>' />
    </div>
</body>
</html>
