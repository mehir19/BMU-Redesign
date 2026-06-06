<?php
session_start();
require_once 'db.php';

// Authentication Check Guard
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Handle Logout Action
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: login.php");
    exit;
}

$message = "";

// Handle Insert Action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_employee'])) {
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

    $sql = "INSERT INTO employees (employee_id, name, email, phone, job_title, department, salary, hire_date, status, address) 
            VALUES ('$emp_id', '$name', '$email', '$phone', '$job_title', '$department', $salary, '$hire_date', '$status', '$address')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert success'>Employee database record initialized.</div>";
    } else {
        $message = "<div class='alert error'>Error: " . $conn->error . "</div>";
    }
}

// Handle Record Deletion Action
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($conn->query("DELETE FROM employees WHERE id = $id") === TRUE) {
        header("Location: admin_dashboard.php");
        exit;
    }
}

$result = $conn->query("SELECT * FROM employees ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Administrative Workspace</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f1f5f9; padding: 2rem; color: #0f172a; margin: 0; }
        .container { max-width: 1400px; margin: 0 auto; display: grid; grid-template-columns: 350px 1fr; gap: 2rem; }
        header { grid-column: 1 / -1; display: flex; justify-content: space-between; align-items: center; background: white; padding: 1rem 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .user-badge { display: flex; align-items: center; gap: 1rem; }
        .logout-btn { background: #ef4444; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 6px; font-size: 0.9rem; }
        .card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); height: fit-content; }
        h2 { font-size: 1.2rem; margin-bottom: 1.2rem; padding-bottom: 0.5rem; border-bottom: 2px solid #f1f5f9; }
        .form-group { margin-bottom: 0.9rem; }
        label { display: block; margin-bottom: 0.35rem; font-size: 0.85rem; font-weight: 500; }
        .form-control { width: 100%; padding: 0.55rem; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; }
        .btn-submit { background: #17662b; color: white; border: none; width: 100%; padding: 0.7rem; border-radius: 6px; cursor: pointer; font-weight: 600; margin-top: 0.5rem; }
        .btn-submit:hover { background: #135022; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        th, td { padding: 0.75rem; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; color: #64748b; font-weight: 600; }
        .btn-delete { color: #ef4444; text-decoration: none; font-weight: 600; }
        .alert { padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; }
        .success { background: #dcfce7; color: #14532d; }
        .error { background: #fef2f2; color: #7f1d1d; }
    </style>
</head>
<body>

<div class="container">
    <header>
        <div>
            <h1 style="font-size: 1.5rem; margin: 0;">Management Suite</h1>
            <p style="color: #64748b; margin: 0; font-size: 0.9rem;">Authorized Administrative Console Access</p>
        </div>
        <div class="user-badge">
            <span>Operator: <strong><?php echo htmlspecialchars($_SESSION['admin_user']); ?></strong></span>
            <a href="admin_dashboard.php?action=logout" class="logout-btn">Logout</a>
        </div>
    </header>

    <div class="card">
        <h2>Personnel Intake Form</h2>
        <?php echo $message; ?>
        <form action="admin_dashboard.php" method="POST">
            <div class="form-group"><label>Corporate ID</label><input type="text" name="employee_id" class="form-control" placeholder="EMP-2026-01" required></div>
            <div class="form-group"><label>Full Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label>Email Address</label><input type="email" name="email" class="form-control" required></div>
            <div class="form-group"><label>Phone Number</label><input type="text" name="phone" class="form-control" required></div>
            <div class="form-group"><label>Job Title</label><input type="text" name="job_title" class="form-control" placeholder="Senior Analyst" required></div>
            <div class="form-group">
                <label>Department</label>
                <select name="department" class="form-control" required>
                    <option value="Administration">Administration</option>
                    <option value="Engineering">Engineering</option>
                    <option value="Human Resources">Human Resources</option>
                    <option value="Finance">Finance</option>
                </select>
            </div>
            <div class="form-group"><label>Annual Salary ($)</label><input type="number" step="0.01" name="salary" class="form-control" required></div>
            <div class="form-group"><label>Hire Date</label><input type="date" name="hire_date" class="form-control" required></div>
            <div class="form-group">
                <label>Operational Status</label>
                <select name="status" class="form-control">
                    <option value="Active">Active</option>
                    <option value="On Leave">On Leave</option>
                    <option value="Terminated">Terminated</option>
                </select>
            </div>
            <div class="form-group"><label>Residential Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
            <button type="submit" name="add_employee" class="btn-submit">Commit Record</button>
        </form>
    </div>

    <div class="card">
        <h2>Master Ledger Records (<?php echo $result->num_rows; ?>)</h2>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Assignment</th>
                        <th>Comp. (Annual)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><code><?php echo htmlspecialchars($row['employee_id']); ?></code></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                                <span style="font-size:0.8rem; color:#64748b;"><?php echo htmlspecialchars($row['email']); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($row['job_title']); ?><br><span style="font-size:0.8rem; color:#64748b;"><?php echo htmlspecialchars($row['department']); ?></span></td>
                            <td>Tk.<?php echo number_format($row['salary'], 2); ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a href="edit_employee.php?id=<?php echo $row['id']; ?>" style="color: #4f46e5; text-decoration: none; font-weight: 600; margin-right: 10px;">Edit</a>
                                <a href="admin_dashboard.php?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Permanently expunge this operational record?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>