<?php
require_once 'core/dbConfig.php';

$currentUser = $_SESSION['user_id'] ?? null;

if (!$currentUser) {
  header("Location: login.php");
  exit();
}

// Retrieve activity logs from the database
$logQuery = "SELECT al.log_id, u.username, al.action, al.record_id, al.action_date 
             FROM activity_log al
             JOIN user u ON al.user_id = u.user_id
             ORDER BY al.action_date DESC";
$statement = $pdo->prepare($logQuery);
$statement->execute();

$activityLogs = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activity Log</title>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-50 via-indigo-100 to-purple-200">

  <?php include 'navbar.php'; ?>

  <div class="container mx-auto py-8 px-4">
    <h1 class="text-4xl font-bold mb-8 text-center text-indigo-900">
      Activity Logs
    </h1>

    <!-- Activity Logs Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-2xl">
      <table class="w-full text-sm text-gray-800 border-collapse border border-gray-300">
        <thead class="bg-indigo-500 text-white">
          <tr>
            <th class="py-4 px-6 border-b text-left font-medium">Log ID</th>
            <th class="py-4 px-6 border-b text-left font-medium">Username</th>
            <th class="py-4 px-6 border-b text-left font-medium">Action</th>
            <th class="py-4 px-6 border-b text-left font-medium">Record ID</th>
            <th class="py-4 px-6 border-b text-left font-medium">Action Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($activityLogs as $logEntry): ?>
            <tr class="hover:bg-indigo-50 transition-all duration-300">
              <td class="py-3 px-6 border-b"><?php echo $logEntry['log_id']; ?></td>
              <td class="py-3 px-6 border-b"><?php echo htmlspecialchars($logEntry['username']); ?></td>
              <td class="py-3 px-6 border-b"><?php echo htmlspecialchars($logEntry['action']); ?></td>
              <td class="py-3 px-6 border-b"><?php echo $logEntry['record_id']; ?></td>
              <td class="py-3 px-6 border-b"><?php echo $logEntry['action_date']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>

</html>
