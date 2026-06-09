<?php
// Security Verification: Ensure parent context is active
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') { 
    exit("Unauthorized Access Blocked."); 
}

$notification = "";

// 1. Process the Role Assignment Action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_role'])) {
    $target_user_id = intval($_POST['user_id']);
    $assigned_role = $conn->real_escape_string($_POST['role']);
    
    // Safety Net: Prevent the logged-in admin from accidentally demoting themselves
    if ($target_user_id == $_SESSION['user_id']) {
        $notification = "<p style='color:#c53030; background:#fff5f5; padding:10px; border-left:4px solid #c53030;'>Safety Warning: You cannot alter your own admin privileges while active.</p>";
    } else {
        $update_query = "UPDATE users SET role = '$assigned_role' WHERE id = $target_user_id";
        if ($conn->query($update_query) === TRUE) {
            $notification = "<p style='color:#2f855a; background:#f0fff4; padding:10px; border-left:4px solid #38a169;'>User assignment successfully adjusted to: <strong>$assigned_role</strong></p>";
        } else {
            $notification = "<p style='color:#c53030;'>Execution Error: " . $conn->error . "</p>";
        }
    }
}

// 2. Fetch all system users to populate the management dashboard grid
$users_list = $conn->query("SELECT id, username, email, role FROM users ORDER BY username ASC");
?>

<h2>Institutional Privilege Control</h2>
<p style="color:#718096; font-size:14px; margin-bottom:20px;">Review the registry roster below to upgrade Authors to peer Reviewers or administrative Editors.</p>

<?= $notification ?>

<table>
    <thead>
        <tr>
            <th>User Account Name</th>
            <th>Email Credentials</th>
            <th>Current Role Mode</th>
            <th>Modify Privileges / Save Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($user = $users_list->fetch_assoc()): ?>
        <tr>
            <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
                <span style="font-weight:bold; color: <?php 
                    if($user['role'] == 'Admin') echo '#2b6cb0';
                    elseif($user['role'] == 'Reviewer') echo '#e67e22';
                    else echo '#4a5568';
                ?>;">
                    <?= $user['role'] ?>
                </span>
            </td>
            <td>
                <form action="" method="POST" style="display:flex; gap:10px; align-items:center;">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    
                    <select name="role" required>
                        <option value="Author" <?= $user['role'] == 'Author' ? 'selected' : '' ?>>Author (Default Submission Profile)</option>
                        <option value="Reviewer" <?= $user['role'] == 'Reviewer' ? 'selected' : '' ?>>Reviewer (Peer Verification Access)</option>
                        <option value="Admin" <?= $user['role'] == 'Admin' ? 'selected' : '' ?>>Admin / Editorial Executive</option>
                    </select>
                    
                    <button type="submit" name="update_role" class="btn-save">Apply Role</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>