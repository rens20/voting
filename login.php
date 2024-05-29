<?php
require_once __DIR__ . '../config/configuration.php';
require_once __DIR__ . '../config/validation.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lrn = $_POST['lrn'];

    $user = ValidateLogin($lrn);

    if (empty($user)) {
        echo "Login Failed";
    } else {
        session_start();
        $validate = $user['type'];
        $user_id = $user['id'];

        if ($validate == 'admin') {
            $_SESSION['token'] = $validate;
            header("Location: ./public/admin.php");
            exit();
        } elseif ($validate == 'user') {
            $_SESSION['token'] = $validate;
            header("Location: ./public/user.php?user_id=$user_id");
            exit();
        } else {
            echo "Invalid user type"; 
        }
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Login</h2>
            </div>
            <form action="" method="post" class="mt-8 space-y-6">
                <div class="mb-4">
                    <label for="lrn" class="block text-gray-700">LRN:</label>
                    <input type="text" id="lrn" name="lrn" placeholder="input your LRN number" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M4 8V6a4 4 0 118 0v2h2a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V9a1 1 0 011-1h2zm2-3a2 2 0 012-2h4a2 2 0 012 2v2H6V5zm2 5h4v1H8V10z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
