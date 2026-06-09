<?php
include 'db.php';
$view = $_GET['view'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Academic Research Journal Portal</title>
    <link rel="stylesheet" href="css/frontend.css">
</head>
<body>

    <header>
        <div class="logo">ScholarJournal</div>
        <nav>
            <a href="index.php?view=home" class="<?= $view == 'home' ? 'active' : '' ?>">Home / Articles</a>
            <a href="index.php?view=about" class="<?= $view == 'about' ? 'active' : '' ?>">About Us</a>
            <a href="index.php?view=contact" class="<?= $view == 'contact' ? 'active' : '' ?>">Contact</a>
        </nav>
    </header>

    <div class="wrapper">
        
        <main class="main-content">
            <?php if ($view == 'about'): ?>
                <h2>About Our Journal</h2>
                <p>ScholarJournal is a peer-reviewed open-access platform dedicated to publishing structural breakthroughs in research across modern multi-disciplinary domains.</p>
            <?php elseif ($view == 'contact'): ?>
                <h2>Contact Editorial Office</h2>
                <p>For formatting inquiries or publication processing, email us at: <strong>support@scholarjournal.org</strong></p>
            <?php else: ?>
                <h2>Current Published Volume</h2>
                <?php
                $query = "SELECT a.*, u.username FROM articles a JOIN users u ON a.author_id = u.id WHERE a.status = 'Published' ORDER BY a.id DESC";
                $published = $conn->query($query);
                if ($published && $published->num_rows > 0):
                    while($row = $published->fetch_assoc()):
                ?>
                        <div class="article-card">
                            <h3><?= htmlspecialchars($row['title']) ?></h3>
                            <span class="meta-tag">Published Volume Record</span>
                            <p style="font-size:14px; color:#4a5568; margin-bottom:8px;"><strong>Lead Investigator:</strong> <?= htmlspecialchars($row['username']) ?></p>
                            <p><?= htmlspecialchars($row['abstract']) ?></p><br>
                            <a href="<?= htmlspecialchars($row['formatted_file_path']) ?>" download style="color:#3498db; text-decoration:none; font-weight:600;">Download Complete Blueprint PDF &rarr;</a>
                        </div>
                <?php endwhile; else: ?>
                    <p style="color: #718096;">No articles have been officially published in this issue yet.</p>
                <?php endif; ?>
            <?php endif; ?>
        </main>

        <aside class="sidebar-panel">
            <?php if (isset($_GET['action']) && $_GET['action'] == 'register'): ?>
                <h2>Author Signup Portal</h2>
                <p style="font-size:13px; color:#718096; margin-bottom:15px;">Create your account below to access the manuscript submission queue tools.</p>
                
                <form action="process_registration.php" method="POST" class="login-form">
                    <label>Full Structural Name</label>
                    <input type="text" name="username" required placeholder="Dr. Jane Doe">
                    <label>Valid Institutional Email Address</label>
                    <input type="email" name="email" required placeholder="jane@university.edu">
                    <label>Secure System Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                    <button type="submit">Complete Registry Registration</button>
                </form>
                <div class="reg-box"><a href="index.php" style="color:#3498db; text-decoration:none;">&larr; Return to Workspace Login</a></div>

            <?php else: ?>
                <h2>Portal Login Center</h2>
                <p style="font-size: 13px; color: #718096; margin-bottom: 15px;">Choose your matching role tab below to clear workspace barriers:</p>
                
                <?php if (isset($_GET['login_error'])) echo '<div class="error-msg">'.htmlspecialchars($_GET['login_error']).'</div>'; ?>
                <?php if (isset($_GET['reg_success'])) echo '<div class="error-msg" style="color:green; background:#f0fff4; border-color:#38a169;">Registration successful! Sign in below.</div>'; ?>

                <div class="tab-container">
                    <button class="login-tab active" onclick="switchLoginTab(event, 'author-tab')">Author</button>
                    <button class="login-tab" onclick="switchLoginTab(event, 'reviewer-tab')">Reviewer</button>
                    <button class="login-tab" onclick="switchLoginTab(event, 'admin-tab')">Admin</button>
                </div>

                <div id="author-tab" class="tab-content active">
                    <form action="process_tab_login.php" method="POST" class="login-form">
                        <input type="hidden" name="intended_role" value="Author">
                        <label>Author Email Address</label>
                        <input type="email" name="email" required placeholder="author@journal.com">
                        <label>Secure Password</label>
                        <input type="password" name="password" required placeholder="••••••••">
                        <button type="submit">Sign In as Author</button>
                    </form>
                </div>

                <div id="reviewer-tab" class="tab-content">
                    <form action="process_tab_login.php" method="POST" class="login-form">
                        <input type="hidden" name="intended_role" value="Reviewer">
                        <label>Reviewer Email</label>
                        <input type="email" name="email" required placeholder="reviewer@journal.com">
                        <label>Secure Password</label>
                        <input type="password" name="password" required placeholder="••••••••">
                        <button type="submit">Sign In to Peer Pool</button>
                    </form>
                </div>

                <div id="admin-tab" class="tab-content">
                    <form action="process_tab_login.php" method="POST" class="login-form">
                        <input type="hidden" name="intended_role" value="Admin">
                        <label>Admin Editor Email</label>
                        <input type="email" name="email" required placeholder="editor@journal.com">
                        <label>Secure Password</label>
                        <input type="password" name="password" required placeholder="••••••••">
                        <button type="submit">Log Into Console Control</button>
                    </form>
                </div>

                <div class="reg-box">
                    Don't have an account? <br><a href="index.php?action=register" style="color:#3498db; font-weight:600; text-decoration:none;">Register New Author Account &rarr;</a>
                </div>
            <?php endif; ?>
        </aside>
    </div>

    <script>
    function switchLoginTab(evt, tabId) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }
        tablinks = document.getElementsByClassName("login-tab");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabId).classList.add("active");
        evt.currentTarget.classList.add("active");
    }
    </script>
</body>
</html>