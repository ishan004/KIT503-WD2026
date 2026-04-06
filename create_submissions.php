<?php
session_start();
include('db_conn.php');

$emailChecked = false;
$existingUser = null;
$existingSubmission = null;

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$affiliation = trim($_POST['affiliation'] ?? '');

if (isset($_POST['check_email'])) {
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = :email");
    $stmt->execute([':email' => $email]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        $subStmt = $pdo->prepare("
            SELECT *
            FROM Submissions
            WHERE User_id = :user_id
            LIMIT 1
        ");
        $subStmt->execute([':user_id' => $existingUser['id']]);
        $existingSubmission = $subStmt->fetch(PDO::FETCH_ASSOC);
    }

    $emailChecked = true;
}

$flash = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Paper</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navbar">
    <div class="container">
        <a class="brand" href="index.php">Conference Management System</a>
        <div class="nav-buttons">
            <a href="index.php" class="btn-outline nav-link">Home</a>
            <a href="submissions.php" class="btn-outline nav-link">Submissions</a>
            <a href="create_submissions.php" class="btn-outline nav-link active">Submit Paper</a>
        </div>
    </div>
</nav>

<main class="content-area">
    <div class="wrapper">
        <div class="details-box create-page-box">
            <div class="create-header">
                <div>
                    <p class="page-kicker">Tutorial 4</p>
                    <h1 class="create-title">Submit a New Paper</h1>
                    <p class="create-subtitle">
                        Enter the author details first. We will check whether the author already exists before allowing a new submission.
                    </p>
                </div>
                <a href="submissions.php" class="secondary-btn">Back to Submissions</a>
            </div>

            <?php if ($flash): ?>
                <div class="alert-box alert-success">
                    <?php echo htmlspecialchars($flash); ?>
                </div>
            <?php endif; ?>

            <!-- STEP 1 -->
            <div class="pretty-card">
                <div class="section-badge">Step 1</div>
                <h2 class="section-title">Author Details</h2>
                <p class="section-text">Fill in the author information to continue.</p>

                <form method="post">
                    <div class="pretty-grid">
                        <div class="form-group">
                            <label for="name">Author Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="<?php echo htmlspecialchars($name); ?>"
                                placeholder="Enter full name"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="<?php echo htmlspecialchars($email); ?>"
                                placeholder="Enter email address"
                                required
                            >
                        </div>

                        <div class="form-group pretty-full">
                            <label for="affiliation">Affiliation</label>
                            <input
                                type="text"
                                id="affiliation"
                                name="affiliation"
                                value="<?php echo htmlspecialchars($affiliation); ?>"
                                placeholder="Enter organisation / university"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="submissions.php" class="secondary-btn">Cancel</a>
                        <button type="submit" name="check_email" class="primary-btn">Check Author</button>
                    </div>
                </form>
            </div>

            <?php if ($emailChecked && $existingUser && $existingSubmission): ?>
                <div class="pretty-card warning-card">
                    <div class="section-badge warning-badge">Already Submitted</div>
                    <h2 class="section-title">This author already has a submission</h2>
                    <p class="section-text">
                        A paper is already linked to this email address, so a new paper cannot be added.
                    </p>

                    <div class="submission-preview-grid">
                        <div class="preview-block">
                            <h3>Author Information</h3>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($existingUser['Name']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($existingUser['Email']); ?></p>
                            <p><strong>Affiliation:</strong> <?php echo htmlspecialchars($existingUser['affiliation']); ?></p>
                        </div>

                        <div class="preview-block">
                            <h3>Existing Paper</h3>
                            <p><strong>Title:</strong> <?php echo htmlspecialchars($existingSubmission['title']); ?></p>
                            <p><strong>Type:</strong> <?php echo htmlspecialchars($existingSubmission['paper_type']); ?></p>
                            <p><strong>Abstract:</strong><br><?php echo nl2br(htmlspecialchars($existingSubmission['abstract'])); ?></p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="details.php?id=<?php echo (int)$existingSubmission['id']; ?>" class="success-btn">View Existing Paper</a>
                        <a href="submissions.php" class="secondary-btn">Back</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($emailChecked && (!$existingUser || !$existingSubmission)): ?>
                <div class="pretty-card success-card">
                    <div class="section-badge success-badge">Step 2</div>
                    <h2 class="section-title">Paper Details</h2>
                    <p class="section-text">No existing submission was found for this author. You can add a new paper below.</p>

                    <form action="engine.php" method="post">
                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        <input type="hidden" name="affiliation" value="<?php echo htmlspecialchars($affiliation); ?>">

                        <div class="summary-bar">
                            <div><strong>Author:</strong> <?php echo htmlspecialchars($name); ?></div>
                            <div><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></div>
                            <div><strong>Affiliation:</strong> <?php echo htmlspecialchars($affiliation); ?></div>
                        </div>

                        <div class="pretty-grid">
                            <div class="form-group">
                                <label for="paper_type">Type of Paper</label>
                                <select id="paper_type" name="paper_type" required>
                                    <option value="Paper">Paper</option>
                                    <option value="Working group">Working group</option>
                                    <option value="Poster">Poster</option>
                                    <option value="Doctoral consortium">Doctoral consortium</option>
                                    <option value="Tips, techniques & courseware">Tips, techniques & courseware</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Paper Title</label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    placeholder="Enter paper title"
                                    required
                                >
                            </div>

                            <div class="form-group pretty-full">
                                <label for="abstract">Abstract</label>
                                <textarea
                                    id="abstract"
                                    name="abstract"
                                    rows="7"
                                    placeholder="Write the paper abstract here..."
                                    required
                                ></textarea>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="submissions.php" class="secondary-btn">Cancel</a>
                            <button type="submit" name="create_submission" class="success-btn">Add Paper</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
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