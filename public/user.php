<?php
require_once(__DIR__ . '/../config/validation.php');


session_start();
if (!isset($_SESSION['token']) || $_SESSION['token'] !== 'user') {
    header("Location: ../login.php"); 
    exit();
}
require_once('connection.php');

// Fetch voters from the database
$sql_fetch_data = "SELECT id, name, officer, vote_counter, image, grade, section, motto FROM voters";
$stmt = $conn->query($sql_fetch_data);
$voters = $stmt->fetchAll(PDO::FETCH_ASSOC);

$email = '';

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    
    $sql_fetch_email = "SELECT email FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql_fetch_email);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $email = htmlspecialchars($user['email']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<header class="bg-blue-500 py-4">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <img src="../image/logo.jpg" alt="School Logo" class="h-16 w-auto rounded-full opacity-50">
                <h1 class="text-white text-2xl font-bold ml-4">Kasiglahan Village National High School</h1>
            </div>
            <a href="../index.php" class="text-white">Logout</a>
            
        </div>
    </div>
</header>
<p class="text-gray-600 font-semibold text-center text-3xl">Welcome, <?php echo $email; ?></p>
<div class="container mx-auto px-4 py-8">
    <form id="votingForm">
    <?php
    // Array to store voters based on officer type
    $voters_by_type = array(
        'President' => array(),
        'Vice President' => array(),
        'PIO' => array(),
        'Secretary' => array(),
        'Treasurer' => array(),
        'Auditor' => array(),
        'Protocol Officer' => array(),
        'Representative' => array()
    );

    // Group voters by officer type
    foreach ($voters as $voter) {
        $voters_by_type[$voter['officer']][] = $voter;
    }

    // Display voters by officer type
    foreach ($voters_by_type as $officer => $voters):
    ?>
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-4 text-gray-800"><?php echo htmlspecialchars($officer); ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($voters as $voter): ?>
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <div class="px-6 py-4">
                           <img src="<?php echo htmlspecialchars($voter['image']); ?>" alt="Voter Image" class="w-full h-auto object-cover object-center">

                            <h3 class="text-lg font-semibold mb-2 text-center"><?php echo htmlspecialchars($voter['name']); ?></h3>
                            <p class="text-gray-700">Officer: <?php echo htmlspecialchars($voter['officer']); ?></p>
                            <p class="text-gray-700">Grade: <?php echo htmlspecialchars($voter['grade']); ?></p>
                            <p class="text-gray-700">Section: <?php echo htmlspecialchars($voter['section']); ?></p>
                           <p class="text-gray-700">Motto: <?php echo htmlspecialchars($voter['motto']); ?></p>
                        </div>
                        <div class="px-6 py-4 bg-blue-500 border-t border-gray-200 text-center">
                            <label>
                                <input type="radio" name="<?php echo htmlspecialchars($officer); ?>" value="<?php echo htmlspecialchars($voter['id']); ?>" class="mr-2">
                                Vote
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <button type="button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition-all duration-300 ease-in-out" onclick="submitVote()">Submit Vote</button>
    </form>
</div>
<script>function submitVote() {
    const form = document.getElementById('votingForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData).toString();

    Swal.fire({
        title: 'Are you sure?',
        text: 'Once voted, you cannot vote again!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit vote',
        cancelButtonText: 'No, cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_vote.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    var response = JSON.parse(xhr.responseText);
                    if (xhr.status == 200) {
                        Swal.fire('Voted!', 'Your vote has been submitted.', 'success').then(() => {
                            window.location.href = '../index.php'; 
                        });
                    } else {
                        Swal.fire('Error!', response.error, 'error');
                    }
                }
            };
            xhr.send(params);
        }
    });
}

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
