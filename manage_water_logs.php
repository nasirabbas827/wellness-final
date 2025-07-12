<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include('config.php');

$user_id = $_SESSION["user_id"];
$success = $error = "";

// Defaults for form fields
$edit_id = $edit_quantity = $edit_unit = $edit_date = "";
$editing = false;

// If edit request, fetch that log
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $sql = "SELECT * FROM WaterLogs WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $edit_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($log = mysqli_fetch_assoc($result)) {
        $editing = true;
        $edit_quantity = $log['quantity'];
        $edit_unit = $log['unit'];
        $edit_date = $log['log_date'];
    }
    mysqli_stmt_close($stmt);
}

// Add new log
if (isset($_POST['add'])) {
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $log_date = $_POST['log_date'];

    $sql = "INSERT INTO WaterLogs (user_id, quantity, unit, log_date) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "idss", $user_id, $quantity, $unit, $log_date);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Log added successfully.";
    } else {
        $error = "Error adding log.";
    }
    mysqli_stmt_close($stmt);
}

// Update existing log
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $log_date = $_POST['log_date'];

    $sql = "UPDATE WaterLogs SET quantity = ?, unit = ?, log_date = ? WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "dssii", $quantity, $unit, $log_date, $id, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Log updated successfully.";
    } else {
        $error = "Error updating log.";
    }
    mysqli_stmt_close($stmt);
}

// Delete log
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM WaterLogs WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $success = "Log deleted.";
}

// Fetch all logs
$logs = [];
$sql = "SELECT * FROM WaterLogs WHERE user_id = ? ORDER BY log_date DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    $logs[] = $row;
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Water Logs</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div>
    <h2>Manage Water Logs</h2>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post">
        <h3><?php echo $editing ? "Edit" : "Add"; ?> Water Log</h3>

        <label>Quantity:</label>
        <input type="number" step="0.1" name="quantity" value="<?php echo htmlspecialchars($edit_quantity); ?>" required>

        <label>Unit:</label>
        <select name="unit" required>
            <option value="ml" <?php if ($edit_unit === "ml") echo 'selected'; ?>>ml</option>
            <option value="glass" <?php if ($edit_unit === "glass") echo 'selected'; ?>>glass</option>
        </select>

        <label>Date:</label>
        <input type="date" name="log_date" value="<?php echo htmlspecialchars($edit_date); ?>" required>

        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
            <input type="submit" name="update" value="Update Log">
            <a href="manage_water_logs.php">Cancel</a>
        <?php else: ?>
            <input type="submit" name="add" value="Add Log">
        <?php endif; ?>
    </form>

    <hr>

    <h3>Your Water Logs</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Date</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?php echo $log['log_date']; ?></td>
                <td><?php echo $log['quantity']; ?></td>
                <td><?php echo $log['unit']; ?></td>
                <td>
                    <a href="?edit=<?php echo $log['id']; ?>">Edit</a> |
                    <a href="?delete=<?php echo $log['id']; ?>" onclick="return confirm('Delete this log?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
