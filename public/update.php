<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $officer = $_POST['officer'];
    $grade = $_POST['grade'];
    $section = $_POST['section'];
    $motto = $_POST['motto'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFileName = $_FILES['image']['name'];
        $imageTempName = $_FILES['image']['tmp_name'];
        $imageUploadPath = 'upload/' . $imageFileName;

        if (move_uploaded_file($imageTempName, $imageUploadPath)) {
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
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        try {
            $stmt = $conn->prepare("SELECT * FROM voters WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $voter = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        header("Location: admin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Voter</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-3xl font-bold mb-4">Update Voter</h1>
    <form action="update.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $voter['id']; ?>">
        <input type="text" name="name" placeholder="Enter name" class="border border-gray-300 rounded-md px-4 py-2 mb-2" value="<?php echo $voter['name']; ?>">
        <input type="text" name="grade" placeholder="Grade" class="border border-gray-300 rounded-md px-4 py-2 mb-2" value="<?php echo $voter['grade']; ?>">
        <input type="text" name="section" placeholder="Section" class="border border-gray-300 rounded-md px-4 py-2 mb-2" value="<?php echo $voter['section']; ?>">
        <input type="text" name="motto" placeholder="Motto" class="border border-gray-300 rounded-md px-4 py-2 mb-2" value="<?php echo $voter['motto']; ?>">
        <select name="officer" class="border border-gray-300 rounded-md px-4 py-2 mb-2">
            <option value="President" <?php echo $voter['officer'] == 'President' ? 'selected' : ''; ?>>President</option>
            <option value="Vice President" <?php echo $voter['officer'] == 'Vice President' ? 'selected' : ''; ?>>Vice President</option>
            <option value="PIO" <?php echo $voter['officer'] == 'PIO' ? 'selected' : ''; ?>>PIO</option>
            <option value="Secretary" <?php echo $voter['officer'] == 'Secretary' ? 'selected' : ''; ?>>Secretary</option>
            <option value="Auditor" <?php echo $voter['officer'] == 'Auditor' ? 'selected' : ''; ?>>Auditor</option>
            <option value="Treasurer" <?php echo $voter['officer'] == 'Treasurer' ? 'selected' : ''; ?>>Treasurer</option>
            <option value="Author" <?php echo $voter['officer'] == 'Author' ? 'selected' : ''; ?>>Author</option>
            <option value="Protocol officers" <?php echo $voter['officer'] == 'Protocol officers' ? 'selected' : ''; ?>>Protocol officers</option>
            <option value="Representative" <?php echo $voter['officer'] == 'Representative' ? 'selected' : ''; ?>>Representative</option>
        </select>
        <input type="file" name="image" accept="image/*" class="border border-gray-300 rounded-md px-4 py-2 mb-2">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Update</button>
    </form>
</body>
</html>
