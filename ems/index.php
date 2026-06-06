<?php
require_once 'db.php';
$result = $conn->query("SELECT * FROM employees WHERE status != 'Terminated' ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Corporate Directory</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f8fafc; padding: 2.5rem; color: #1e293b; }
        .wrapper { max-width: 1200px; margin: 0 auto; }
        .navbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0; }
        .navbar h1 { color: #0f172a; margin: 0; font-size: 1.75rem; }
        .login-btn { background: #4f46e5; color: white; padding: 0.6rem 1.2rem; text-decoration: none; border-radius: 6px; font-weight: 500; }
        .login-btn:hover { background: #4338ca; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem; }
        .emp-id { font-size: 0.75rem; background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 4px; color: #64748b; font-weight: 600; }
        .status-badge { font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 600; }
        .status-active { background: #dcfce7; color: #15803d; }
        .status-leave { background: #fef9c3; color: #a16207; }
        .name { font-size: 1.25rem; font-weight: 600; color: #1e293b; margin-bottom: 0.25rem; }
        .title { font-size: 0.95rem; color: #4f46e5; font-weight: 500; margin-bottom: 1rem; }
        .details { font-size: 0.875rem; color: #475569; border-top: 1px dashed #e2e8f0; padding-top: 0.75rem; }
        .details p { margin: 0.4rem 0; display: flex; justify-content: space-between; }
        .details span { color: #0f172a; font-weight: 500; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="navbar">
            <div>
                <h1>Enterprise Directory</h1>
                <p style="color: #64748b; margin-top: 0.25rem;">Public view of current corporate personnel</p>
            </div>
            <a href="login.php" class="login-btn">Admin Console</a>
        </div>

        <div class="grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <div class="card-header">
                            <span class="emp-id"><?php echo htmlspecialchars($row['employee_id']); ?></span>
                            <span class="status-badge <?php echo $row['status'] == 'Active' ? 'status-active' : 'status-leave'; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </div>
                        <div class="name"><?php echo htmlspecialchars($row['name']); ?></div>
                        <div class="title"><?php echo htmlspecialchars($row['job_title']); ?> &bull; <span style="color:#64748b"><?php echo htmlspecialchars($row['department']); ?></span></div>
                        
                        <div class="details">
                            <p>Email: <span><?php echo htmlspecialchars($row['email']); ?></span></p>
                            <p>Phone: <span><?php echo htmlspecialchars($row['phone']); ?></span></p>
                            <p>Joined: <span><?php echo date("M d, Y", strtotime($row['hire_date'])); ?></span></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align: center; color: #64748b; padding: 3rem;">No active employee records found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>