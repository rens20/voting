<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Register</title>
 
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {


            background-image: url('https://scontent.fmnl4-4.fna.fbcdn.net/v/t1.6435-9/96454280_103279554723842_9040174458861518848_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeHQHWbC1heWmEiCWdO_qZyfjGcRu7Q3Kp-MZxG7tDcqn0L0s8SWDU5e30q38lCI1ToeoXAyBB0i9SdTNK56cEMN&_nc_ohc=MamAJYA8sUoQ7kNvgFTBgpj&_nc_ht=scontent.fmnl4-4.fna&oh=00_AfDRErq6kePp3qVpASNbQ_2Cv8bpVKfw6IkN-H9FfngV1w&oe=6651CF87'); 
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            margin-bottom: 50%;
            min-height: 70%;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: rgba(0, 0, 0, 0.6); 
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: white;
            font-size: 1.5rem;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            text-align: center;
            color: white;/
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>
            <img src="./image/logo.jpg" alt="School Logo" class="h-16 w-auto rounded opacity-0.5" style="border-radius: 50%;">
            Kasiglahan Village National High School
        </h1>
        <div class="flex items-center space-x-4">
        
            <button id="loginBtn" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition duration-300 ease-in-out transform hover:scale-105">
                Login
            </button>
        </div>
    </div>
   

    <script>
        document.getElementById('loginBtn').addEventListener('click', function() {
            // Redirect to login page
            window.location.href = 'login.php';
        });

        document.getElementById('registerBtn').addEventListener('click', function() {
            // Redirect to register page
            window.location.href = 'register.php';
        });
    </script>
</body>

</html>