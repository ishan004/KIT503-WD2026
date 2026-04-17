<?php
require_once 'session_check.php';
require_once 'db_conn.php';
include('db_conn.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("No valid submission ID provided.");
}

$id = (int)$_GET['id'];

$sql = "
    SELECT
        s.id,
        s.title,
        s.paper_type,
        s.abstract,
        u.Name AS author_name,
        u.Email AS author_email,
        u.affiliation
    FROM Submissions s
    JOIN Users u ON s.User_id = u.id
    WHERE s.id = :id
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$submission = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$submission) {
    die("Submission not found.");
}

$flash = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Paper Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php require_once 'nav.php'; ?>

<main class="content-area">
    <div class="wrapper">
        <div class="details-box create-page-box">
            <div class="create-header">
                <div>
                    <p class="page-kicker">Tutorial 4</p>
                    <h1 class="create-title">Update Paper Details</h1>
                    <p class="create-subtitle">
                        Edit the submission information below and save your changes.
                    </p>
                </div>
                <a href="submissions.php" class="secondary-btn">Back to Submissions</a>
            </div>

            <?php if ($flash): ?>
                <div class="alert-box alert-success">
                    <?php echo htmlspecialchars($flash); ?>
                </div>
            <?php endif; ?>

            <div class="pretty-card">
                <div class="section-badge">Edit Submission</div>
                <h2 class="section-title">Current Submission Information</h2>
                <p class="section-text">
                    Update the paper type, title, or abstract. Author details are shown for reference.
                </p>

                <div class="summary-bar">
                    <div><strong>Author:</strong> <?php echo htmlspecialchars($submission['author_name']); ?></div>
                    <div><strong>Email:</strong> <?php echo htmlspecialchars($submission['author_email']); ?></div>
                    <div><strong>Affiliation:</strong> <?php echo htmlspecialchars($submission['affiliation']); ?></div>
                </div>

                <form action="engine.php" method="post">
                    <input type="hidden" name="submission_id" value="<?php echo (int)$submission['id']; ?>">

                    <div class="pretty-grid">
                        <div class="form-group">
                            <label for="paper_type">Type of Paper</label>
                            <select id="paper_type" name="paper_type" required>
                                <option value="Paper" <?php echo $submission['paper_type'] === 'Paper' ? 'selected' : ''; ?>>Paper</option>
                                <option value="Working group" <?php echo $submission['paper_type'] === 'Working group' ? 'selected' : ''; ?>>Working group</option>
                                <option value="Poster" <?php echo $submission['paper_type'] === 'Poster' ? 'selected' : ''; ?>>Poster</option>
                                <option value="Doctoral consortium" <?php echo $submission['paper_type'] === 'Doctoral consortium' ? 'selected' : ''; ?>>Doctoral consortium</option>
                                <option value="Tips, techniques & courseware" <?php echo $submission['paper_type'] === 'Tips, techniques & courseware' ? 'selected' : ''; ?>>Tips, techniques & courseware</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Paper Title</label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="<?php echo htmlspecialchars($submission['title']); ?>"
                                placeholder="Enter paper title"
                                required
                            >
                        </div>

                        <div class="form-group pretty-full">
                            <label for="abstract">Abstract</label>
                            <textarea
                                id="abstract"
                                name="abstract"
                                rows="8"
                                placeholder="Update the paper abstract here..."
                                required
                            ><?php echo htmlspecialchars($submission['abstract']); ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="submissions.php" class="secondary-btn">Cancel</a>
                        <button type="submit" name="finalise_update_submission" class="primary-btn">Update Submission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<footer>
    <div class="container footer-row">
        <p>&copy; 2026 Conference Management System</p>
    </div>
</footer>

<script src="script.js"></script>
</body>
</html>