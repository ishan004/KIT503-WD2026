<?php
include('db_conn.php');

$emailChecked = false;
$existingUser = null;
$existingSubmission = null;

if (isset($_POST['check_email'])) {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        $subStmt = $pdo->prepare("SELECT * FROM Submissions WHERE User_id = :user_id");
        $subStmt->bindParam(':user_id', $existingUser['id'], PDO::PARAM_INT);
        $subStmt->execute();
        $existingSubmission = $subStmt->fetch(PDO::FETCH_ASSOC);
    }

    $emailChecked = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Paper</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Submit a Paper</h1>
    <a href="submissions.php" class="btn btn-secondary mb-3">Back to submissions</a>

    <div class="card p-3 mb-4">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Author</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Affiliation</label>
                <input type="text" name="affiliation" class="form-control" value="<?php echo htmlspecialchars($_POST['affiliation'] ?? ''); ?>" required>
            </div>

            <button type="submit" name="check_email" class="btn btn-primary">Next</button>
        </form>
    </div>

    <?php if ($emailChecked && $existingUser && $existingSubmission): ?>
        <div class="alert alert-warning">
            This author has already submitted a paper.
        </div>

        <div class="card p-3">
            <p><strong>Author:</strong> <?php echo htmlspecialchars($existingUser['Name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($existingUser['Email']); ?></p>
            <p><strong>Affiliation:</strong> <?php echo htmlspecialchars($existingUser['affiliation']); ?></p>
            <p><strong>Paper Title:</strong> <?php echo htmlspecialchars($existingSubmission['title']); ?></p>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($existingSubmission['paper_type']); ?></p>
            <p><strong>Abstract:</strong> <?php echo htmlspecialchars($existingSubmission['abstract']); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($emailChecked && (!$existingUser || !$existingSubmission)): ?>
        <div class="card p-3">
            <form action="engine.php" method="post">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($_POST['name']); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST['email']); ?>">
                <input type="hidden" name="affiliation" value="<?php echo htmlspecialchars($_POST['affiliation']); ?>">

                <div class="mb-3">
                    <label class="form-label">Type of paper</label>
                    <select name="paper_type" class="form-select" required>
                        <option value="Paper">Paper</option>
                        <option value="Working group">Working group</option>
                        <option value="Poster">Poster</option>
                        <option value="Doctoral consortium">Doctoral consortium</option>
                        <option value="Tips, techniques & courseware">Tips, techniques & courseware</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Abstract</label>
                    <textarea name="abstract" class="form-control" rows="5" required></textarea>
                </div>

                <button type="submit" name="create_submission" class="btn btn-success">Add paper</button>
            </form>
        </div>
    <?php endif; ?>
</div>
</body>
</html>