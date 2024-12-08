<?php
require_once 'core/dbConfig.php'; // Include dbConfig for database access

// Check if the photo ID is provided in the URL and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $photoId = $_GET['id'];

  try {
    // Retrieve photo details (name, title, description) from the database
    $selectQuery = "SELECT * FROM photos WHERE photo_id = :photo_id";
    $stmt = $pdo->prepare($selectQuery);
    $stmt->bindParam(':photo_id', $photoId, PDO::PARAM_INT);
    $stmt->execute();

    $photoDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($photoDetails) {
      // If the form is submitted to confirm deletion
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        // Start session and get the user ID (assuming user is logged in)
        session_start();
        $userId = $_SESSION['user_id'] ?? null;

        // Delete the photo record from the database
        $deleteQuery = "DELETE FROM photos WHERE photo_id = :photo_id";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindParam(':photo_id', $photoId, PDO::PARAM_INT);

        if ($deleteStmt->execute()) {
          // Log the deletion action in the activity log
          if ($userId) {
            $logMessage = 'Deleted Photo (ID: ' . $photoId . ')';
            $logQuery = "INSERT INTO activity_log (user_id, action, record_id) VALUES (?, ?, ?)";
            $logStmt = $pdo->prepare($logQuery);
            $logStmt->execute([$userId, $logMessage, $photoId]);
          }

          // Delete the physical photo file from the server
          $filePath = 'uploads/' . $photoDetails['photo_name'];
          if (file_exists($filePath)) {
            unlink($filePath); // Remove the file
          }

          // Redirect to the homepage after successful deletion
          header("Location: index.php");
          exit();
        } else {
          $errorMessage = "Error occurred while deleting the photo from the database.";
        }
      }
    } else {
      $errorMessage = "Photo not found.";
    }
  } catch (PDOException $e) {
    $errorMessage = "Error: " . $e->getMessage();
  }
} else {
  $errorMessage = "Invalid photo ID.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm Photo Deletion</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen">

  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Confirm Deletion</h2>

    <?php if (isset($errorMessage)): ?>
      <div class="bg-red-200 text-red-800 p-3 rounded-md mb-4"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <?php if ($photoDetails): ?>
      <p class="text-center text-lg font-medium text-gray-800 mb-4">Are you sure you want to delete this photo?</p>
      <div class="mb-4">
        <img src="uploads/<?php echo $photoDetails['photo_name']; ?>" alt="Photo"
          class="mx-auto w-full h-auto max-h-48 object-cover rounded-md mb-4">
      </div>
      <p class="text-center text-gray-600 mb-4"><strong>Title:</strong> <?php echo $photoDetails['title']; ?></p>
      <p class="text-center text-gray-600 mb-4"><strong>Description:</strong> <?php echo $photoDetails['description']; ?></p>

      <!-- Deletion Form -->
      <form method="POST">
        <div class="flex justify-center gap-4">
          <button type="submit" name="confirm_delete"
            class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
            Yes, Delete
          </button>
          <a href="index.php"
            class="px-6 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500">
            No, Cancel
          </a>
        </div>
      </form>
    <?php endif; ?>

  </div>

</body>

</html>
