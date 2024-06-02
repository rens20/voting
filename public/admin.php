<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['insert'])) {
        // Retrieve form data
        $name = $_POST['name'];
        $officer = $_POST['officer'];
        $grade = $_POST['grade'];
        $section = $_POST['section'];
        $motto = $_POST['motto'];

        // Check if an image is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageFileName = $_FILES['image']['name'];
            $imageTempName = $_FILES['image']['tmp_name'];
            $imageUploadPath = 'upload/' . $imageFileName;

            // Move the uploaded image to the uploads folder
            if (move_uploaded_file($imageTempName, $imageUploadPath)) {
                // Image uploaded successfully, now insert into database
                try {
                    $stmt = $conn->prepare("INSERT INTO voters (name, officer, grade, section, motto, image) VALUES (:name, :officer, :grade, :section, :motto, :image)");
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':officer', $officer);
                    $stmt->bindParam(':grade', $grade);
                    $stmt->bindParam(':section', $section);
                    $stmt->bindParam(':motto', $motto);
                    $stmt->bindParam(':image', $imageUploadPath);
                    $stmt->execute();
                    header("Location: admin.php");
                    exit();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "No image uploaded.";
        }
    } elseif (isset($_POST['update'])) {
        // Update logic
        $id = $_POST['id'];
        $name = $_POST['name'];
        $officer = $_POST['officer'];
        $grade = $_POST['grade'];
        $section = $_POST['section'];
        $motto = $_POST['motto'];

        // Check if an image is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageFileName = $_FILES['image']['name'];
            $imageTempName = $_FILES['image']['tmp_name'];
            $imageUploadPath = 'upload/' . $imageFileName;

            // Move the uploaded image to the uploads folder
            if (move_uploaded_file($imageTempName, $imageUploadPath)) {
                // Image uploaded successfully, now update the database
                try {
                    $stmt = $conn->prepare("UPDATE voters SET name = :name, officer = :officer, grade = :grade, section = :section, motto = :motto, image = :image WHERE id = :id");
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':officer', $officer);
                    $stmt->bindParam(':grade', $grade);
                    $stmt->bindParam(':section', $section);
                    $stmt->bindParam(':motto', $motto);
                    $stmt->bindParam(':image', $imageUploadPath);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    header("Location: admin.php");
                    exit();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Error uploading image.";
            }
        } else {
            try {
                $stmt = $conn->prepare("UPDATE voters SET name = :name, officer = :officer, grade = :grade, section = :section, motto = :motto WHERE id = :id");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':officer', $officer);
                $stmt->bindParam(':grade', $grade);
                $stmt->bindParam(':section', $section);
                $stmt->bindParam(':motto', $motto);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                header("Location: admin.php");
                exit();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['delete'])) {
        // Delete logic
        $id = $_POST['id'];

        try {
            $stmt = $conn->prepare("DELETE FROM voters WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: admin.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $voters = searchVoters($searchQuery);
} else {
    $voters = fetchAllVoters();
}

function fetchAllVoters() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT id, name, officer, grade, section, motto, vote_counter, image FROM voters");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

function searchVoters($searchQuery) {
    global $conn;
    try {
        $search = '%' . $searchQuery . '%';
        $stmt = $conn->prepare("SELECT id, name, officer, grade, section, motto, vote_counter, image FROM voters WHERE name LIKE :search");
        $stmt->bindParam(':search', $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

// Group voters by officer role
$groupedVoters = [];
foreach ($voters as $voter) {
    $officer = $voter['officer'];
    if (!isset($groupedVoters[$officer])) {
        $groupedVoters[$officer] = [];
    }
    $groupedVoters[$officer][] = $voter;
}

// Calculate percentage of votes for each officer
$officerVotes = [];
foreach ($voters as $voter) {
    $officer = $voter['officer'];
    $voteCounter = intval($voter['vote_counter']);
    if (!isset($officerVotes[$officer])) {
        $officerVotes[$officer] = 0;
    }
    $officerVotes[$officer] += $voteCounter;
}

// Calculate percentage for each officer based on a total of 5000 votes for candidates like President, PIO, Vice President, etc.
$totalVotes = 5000;
$officerPercentages = [];
foreach ($officerVotes as $officer => $votes) {
    $percentage = ($votes / $totalVotes) * 100;
    $officerPercentages[$officer] = $percentage;
}

?>
<!DOC
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .table-image {
            width: 80px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-gray-100 p-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold mb-4">Admin Panel</h1>
        <a href="../index.php" class="text-black font-bold ml-auto">Logout</a>
    </div>
    <form action="" method="get" class="mb-6" enctype="multipart/form-data">
        <div class="flex mb-4">
            <input type="text" name="search" placeholder="Search by name" class="border border-gray-300 rounded-md px-4 py-2 mr-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Search</button>
            <a href="admin.php" class="bg-gray-400 text-white px-4 py-2 rounded-md hover:bg-gray-600 ml-2">Clear</a>
        </div>
    </form>
    <form action="" method="post" class="mb-6" enctype="multipart/form-data">
        <input type="hidden" name="id" value="">
        <input type="text" name="name" placeholder="Enter name" class="border border-gray-300 rounded-md px-4 py-2 mb-2">
        <input type="text" name="grade" placeholder="Grade" class="border border-gray-300 rounded-md px-4 py-2 mb-2">
        <input type="text" name="section" placeholder="Section" class="border border-gray-300 rounded-md px-4 py-2 mb-2">
        <input type="text" name="motto" placeholder="Motto" class="border border-gray-300 rounded-md px-4 py-2 mb-2">
        <select name="officer" class="border border-gray-300 rounded-md px-4 py-2 mb-2">
            <option value="President">President</option>
            <option value="Vice President">Vice President</option>
            <option value="PIO">PIO</option>
            <option value="Secretary">Secretary</option>
            <option value="Auditor">Auditor</option>
            <option value="Treasurer">Treasurer</option>
            <option value="Author">Author</option>
            <option value="Protocol officers">Protocol officers</option>
            <option value="Representative">Representative</option>
        </select>
        <input type="file" name="image" accept="image/*" class="border border-gray-300 rounded-md px-4 py-2 mb-2">
        <button type="submit" name="insert" class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2 hover:bg-blue-600">Insert</button>
        <button type="submit" name="update" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Update</button>
    </form>
    <?php foreach ($groupedVoters as $officer => $voters): ?>
        <h2 class="text-xl font-bold mb-4"><?php echo $officer; ?></h2>
        <table class="min-w-full bg-white border border-gray-300 mb-6">
            
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Officer</th>
                    <th class="py-2 px-4 border-b">Grade</th>
                    <th class="py-2 px-4 border-b">Section</th>
                    <th class="py-2 px-4 border-b">Motto</th>
                    <th class="py-2 px-4 border-b">Vote Counter</th>
                     <th class="py-2 px-4 border-b">Percentage of Votes</th>
                    <th class="py-2 px-4 border-b">Image</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voters as $voter): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo $voter['name']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $voter['officer']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $voter['grade']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $voter['section']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $voter['motto']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $voter['vote_counter']; ?></td>
                         <td class="py-2 px-4 border-b"><?php echo number_format(($voter['vote_counter'] / 5000) * 100, 2) . '%'; ?></td>
                        <td class="py-2 px-4 border-b">
                            <?php if (!empty($voter['image'])): ?>
                                <img src="<?php echo $voter['image']; ?>" class="table-image" alt="Voter Image">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-4 border-b">
                                  <form action="" method="post" class="inline">
                                <input type="hidden" name="id" value="<?php echo $voter['id']; ?>">
                               <a href='update.php?id=<?php echo $voter['id']; ?>' class='bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600'>Update</a>

                            </form>

                            <form action="" method="post" class="inline">
                                <input type="hidden" name="id" value="<?php echo $voter['id']; ?>">
                                <button type="submit" name="delete" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
    <!-- <script>
    function setEditId() {
        // Get the ID of the voter to edit
        let editId = document.getElementById('editId');
        let voterId = prompt("Enter the ID of the voter to edit:");
        if (voterId) {
            editId.value = voterId;
        }
    }
</script> -->
</body>

</html>
