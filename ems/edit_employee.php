<?php
session_start();
require_once 'db.php';

// Authentication Guard
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$message = "";

// 1. FETCH CURRENT EMPLOYEE DATA
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM employees WHERE id = $id");
    
    if ($result && $result->num_rows === 1) {
        $emp = $result->fetch_assoc();
    } else {
        die("Employee record not found.");
    }
} else {
    header("Location: admin_dashboard.php");
    exit;
}

// 2. HANDLE UPDATE QUERY SUBMISSION
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_employee'])) {
    $emp_id     = $conn->real_escape_string($_POST['employee_id']);
    $name       = $conn->real_escape_string($_POST['name']);
    $email      = $conn->real_escape_string($_POST['email']);
    $phone      = $conn->real_escape_string($_POST['phone']);
    $job_title  = $conn->real_escape_string($_POST['job_title']);
    $department = $conn->real_escape_string($_POST['department']);
    $salary     = (float)$_POST['salary'];
    $hire_date  = $conn->real_escape_string($_POST['hire_date']);
    $status     = $conn->real_escape_string($_POST['status']);
    $address    = $conn->real_escape_string($_POST['address']);

    // SQL UPDATE Query
    $update_sql = "UPDATE employees SET 
                    employee_id = '$emp_id', 
                    name = '$name', 
                    email = '$email', 
                    phone = '$phone', 
                    job_title = '$job_title', 
                    department = '$department', 
                    salary = $salary, 
                    hire_date = '$hire_date', 
                    status = '$status', 
                    address = '$address' 
                   WHERE id = $id";

    if ($conn->query($update_sql) === TRUE) {
        // Redirect back to dashboard with instant success visual update
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $message = "<div class='alert error'>Error updating record: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modify Personnel Record</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f1f5f9; padding: 3rem; color: #0f172a; }
        .form-card { background: white; padding: 2.5rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto; }
        h2 { font-size: 1.5rem; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #f1f5f9; color: #1e293b; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group { margin-bottom: 1rem; }
        .full-width { grid-column: span 2; }
        label { display: block; margin-bottom: 0.35rem; font-size: 0.85rem; font-weight: 500; }
        .form-control { width: 100%; padding: 0.6rem; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; font-size: 0.95rem; }
        .form-control:focus { outline: none; border-color: #4f46e5; }
        .actions { margin-top: 1.5rem; display: flex; gap: 1rem; }
        .btn-save { background: #4f46e5; color: white; border: none; flex: 2; padding: 0.75rem; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .btn-save:hover { background: #4338ca; }
        .btn-cancel { background: #e2e8f0; color: #475569; text-decoration: none; text-align: center; flex: 1; padding: 0.75rem; border-radius: 6px; font-weight: 500; }
        .btn-cancel:hover { background: #cbd5e1; }
        .alert { padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; }
        .error { background: #fef2f2; color: #7f1d1d; }
    </style>
</head>
<body>

    <div class="form-card">
        <h2>Modify Information</h2>
        <?php echo $message; ?>
        
        <form action="edit_employee.php?id=<?php echo $id; ?>" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Corporate ID</label>
                    <input type="text" name="employee_id" class="form-control" value="<?php echo htmlspecialchars($emp['employee_id']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($emp['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($emp['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($emp['phone']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" name="job_title" class="form-control" value="<?php echo htmlspecialchars($emp['job_title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select name="department" class="form-control" required>
                        <option value="Administration" <?php if($emp['department'] == 'Administration') echo 'selected'; ?>>Administration</option>
                        <option value="Engineering" <?php if($emp['department'] == 'Engineering') echo 'selected'; ?>>Engineering</option>
                        <option value="Human Resources" <?php if($emp['department'] == 'Human Resources') echo 'selected'; ?>>Human Resources</option>
                        <option value="Finance" <?php if($emp['department'] == 'Finance') echo 'selected'; ?>>Finance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Annual Salary (Tk.)</label>
                    <input type="number" step="0.01" name="salary" class="form-control" value="<?php echo htmlspecialchars($emp['salary']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Hire Date</label>
                    <input type="date" name="hire_date" class="form-control" value="<?php echo htmlspecialchars($emp['hire_date']); ?>" required>
                </div>
                <div class="form-group full-width">
                    <label>Operational Status</label>
                    <select name="status" class="form-control">
                        <option value="Active" <?php if($emp['status'] == 'Active') echo 'selected'; ?>>Active</option>
                        <option value="On Leave" <?php if($emp['status'] == 'On Leave') echo 'selected'; ?>>On Leave</option>
                        <option value="Terminated" <?php if($emp['status'] == 'Terminated') echo 'selected'; ?>>Terminated</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Residential Address</label>
                    <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($emp['address']); ?></textarea>
                </div>
            </div>

            <div class="actions">
                <a href="admin_dashboard.php" class="btn-cancel">Discard Changes</a>
                <button type="submit" name="update_employee" class="btn-save">Apply Updates</button>
            </div>
        </form>
    </div>

</body>
</html>