<?php
session_start();
if (!isset($_SESSION['token']) || $_SESSION['token'] !== 'user') {
    header("Location: ../login.php"); // Redirect to login if not logged in as a user
    exit();
}

$user_name = $_SESSION['user_name'];
require_once('connection.php');

// Fetch voters from the database
$sql_fetch_data = "SELECT id, name, officer, vote_counter, image, grade, section, motto FROM voters";
$stmt = $conn->query($sql_fetch_data);
$voters = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <img src="../image/logo.jpg" alt="School Logo" class="h-16 w-auto rounded opacity-0.5" style="border-radius: 50%;">
                <h1 class="text-white text-2xl font-bold ml-4">Kasiglahan Village National High School</h1>
            </div>
            <a href="../index.php" class="text-white">Logout</a>
        </div>
    </div>
</header>
<h1>Hello <?php echo $user_name; ?></h1>
<div class="container mx-auto px-4 py-8">
    <?php 
    // Array to store voters based on officer type
    $voters_by_type = array(
        'President' => array(),
        'Vice President' => array(),
        'PIO' => array(),
        'Secretary' => array(),
        'Tresurer' => array(),
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
            <h2 class="text-3xl font-bold mb-4 text-gray-800"><?php echo $officer; ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($voters as $voter): ?>
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                         <div class="px-6 py-4">
    <img src="<?php echo $voter['image']; ?>" alt="Voter Image" class="mt-3 w-full h-auto object-cover">
    <h3 class="text-lg font-semibold mb-2 text-center"><?php echo $voter['name']; ?></h3>
    <p class="text-gray-700">Officer: <?php echo $voter['officer']; ?></p>
    <p class="text-gray-700">Grade: <?php echo $voter['grade']; ?></p> <!-- Display grade -->
    <p class="text-gray-700">Section: <?php echo $voter['section']; ?></p> <!-- Display section -->
    <p class="text-gray-700">Motto: <?php echo $voter['motto']; ?></p> <!-- Display motto -->
</div>
       <div class="px-6 py-4 bg-blue-500 border-t border-gray-200 text-center">
    <a href="#"
       id="vote_<?php echo $voter['officer']; ?>_<?php echo $voter['id']; ?>"
       class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition-all duration-300 ease-in-out"
       onclick="castVote(<?php echo $voter['id']; ?>, '<?php echo $voter['officer']; ?>', this)">Vote</a>
</div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function castVote(voterId, officer, button) {
    // Show SweetAlert confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: 'Once voted, you cannot vote again!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, vote',
        cancelButtonText: 'No, cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with voting if confirmed
            castVoteAJAX(voterId, officer, button);
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Do nothing if canceled
        }
    });
}

function castVoteAJAX(voterId, officer, button) {
    // Check if the button is already disabled
    if (!button.disabled) {
        // Send AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_vote.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Update button text and disable it
                button.innerText = "Voted";
                button.disabled = true;

                // Disable all buttons in the same array
                var buttons = document.querySelectorAll('[id^="vote_' + officer + '"]');
                buttons.forEach(function (btn) {
                    btn.disabled = true;
                });
            }
        };
        xhr.send("voter_id=" + voterId);
    }
}

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>
</html>