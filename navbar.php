<?php
require_once 'core/dbConfig.php';  // This file should have session_start()

// Check the current page to handle search functionality
$currentPage = basename($_SERVER['PHP_SELF']);

// Initialize default values
$userId = $_SESSION['user_id'] ?? null;  // Retrieve the user ID from the session if available
$userName = "Guest";  // Default user name as Guest
$userEmail = "guest@example.com";  // Default email as guest email

// Fetch the user data from the database if the user is logged in
if ($userId) {
    $query = "SELECT first_name, last_name, email FROM user WHERE user_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $userName = htmlspecialchars($user['first_name'] . " " . $user['last_name']);
            $userEmail = htmlspecialchars($user['email']);
        }
    }
}

// Extract the first letter from the user's name (first name or full name)
$avatarLetter = strtoupper(substr($userName, 0, 1)); // Get the first letter of the user's first name or full name
?>

<head>
    <!-- Flowbite and TailwindCSS links -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<div class="px-4 py-3 bg-white dark:bg-gray-800">
    <nav class="flex items-center justify-between">
        <!-- Left Side: Links -->
        <div class="flex items-center space-x-6">
            <!-- Mobile Toggle Button -->
            <button type="button" class="block md:hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg p-2" onclick="toggleNavbar()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>

            <!-- Navbar Links (Desktop) -->
            <div id="navbar-links" class="hidden md:flex space-x-6">
                <a href="index.php" class="text-gray-900 dark:text-white hover:text-blue-700 md:text-blue-700">
                    Home
                </a>
                <a href="records.php" class="text-gray-900 dark:text-white hover:text-blue-700 md:text-blue-700">
                    Records
                </a>
                <a href="activitylogs.php" class="text-gray-900 dark:text-white hover:text-blue-700 md:text-blue-700">
                    Activity Logs
                </a>
            </div>
        </div>

        <!-- Centered Header -->
        <div class="flex justify-center flex-1">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome to Your Dashboard</h1>
        </div>

        <!-- Right Side: Buttons and User Menu -->
        <div class="flex items-center space-x-4">

            <!-- Add Record Button -->
            <a href="add_photo.php">
                <button class="px-4 py-2 bg-blue-700 text-white rounded-lg text-sm">Add an Album</button>
            </a>

            <!-- Search Bar (Only on records.php) -->
            <?php if ($currentPage === 'records.php') { ?>
                <div id="search-bar" class="relative hidden md:block">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <form method="GET" action="records.php" onsubmit="return true;">
                        <input type="text" name="search" id="search-navbar" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search record" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" oninput="toggleClearButton()" />
                    </form>
                    <!-- Clear Button -->
                    <button id="clear-btn" class="absolute inset-y-0 end-0 flex items-center pe-3 hidden" onclick="clearSearch()">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6L14 14M6 14L14 6" />
                        </svg>
                    </button>
                </div>
            <?php } ?>

            <!-- Account Dropdown -->
            <div class="relative">
                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown">
                    <span class="sr-only">Open user menu</span>
                    <!-- Circle Avatar with First Letter of User's Name -->
                    <div class="w-8 h-8 bg-blue-700 text-white rounded-full flex items-center justify-center font-semibold">
                        <?= $avatarLetter ?>
                    </div>
                </button>
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white">Welcome, <?= $userName; ?>!</span>
                        <span class="block text-sm text-gray-500 truncate dark:text-gray-400"><?= $userEmail; ?></span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li><a href="signout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>

<script>
    // Function to toggle visibility of the "X" button in search bar
    function toggleClearButton() {
        const searchInput = document.getElementById('search-navbar');
        const clearButton = document.getElementById('clear-btn');
        if (searchInput.value.length > 0) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
        }
    }

    // Function to clear the search input and reset the table view
    function clearSearch() {
        const searchInput = document.getElementById('search-navbar');
        searchInput.value = '';  // Clear the search input field
        toggleClearButton();  // Hide the "X" button

        // Reset the table by reloading the page without the search query
        window.location.href = "records.php"; // Reloads the page and resets the search filter
    }

    // Keep the "X" button visible after form submission
    window.onload = function () {
        const searchInput = document.getElementById('search-navbar');
        const clearButton = document.getElementById('clear-btn');
        if (searchInput.value.length > 0) {
            clearButton.classList.remove('hidden');
        }
    }
</script>
