<?php
include('db_conn.php');

if (!isset($_GET['id'])) {
    die("No submission ID provided.");
}

$id = (int)$_GET['id'];

$sql = "
    SELECT
        s.id,
        s.title,
        s.paper_type,
        s.abstract,
        u.Name AS author_name
    FROM Submissions s
    JOIN Users u ON s.User_id = u.id
    WHERE s.id = :id
";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$submission = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$submission) {
    die("Submission not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Paper Details</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Update Paper Details</h1>
    <a href="submissions.php" class="btn btn-secondary mb-3">Cancel</a>

    <form action="engine.php" method="post" class="card p-3">
        <input type="hidden" name="submission_id" value="<?php echo htmlspecialchars($submission['id']); ?>">

        <div class="mb-3">
            <label class="form-label">Author</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($submission['author_name']); ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Type of paper</label>
            <select name="paper_type" class="form-select" required>
                <option value="Paper" <?php if ($submission['paper_type'] === 'Paper') echo 'selected'; ?>>Paper</option>
                <option value="Working group" <?php if ($submission['paper_type'] === 'Working group') echo 'selected'; ?>>Working group</option>
                <option value="Poster" <?php if ($submission['paper_type'] === 'Poster') echo 'selected'; ?>>Poster</option>
                <option value="Doctoral consortium" <?php if ($submission['paper_type'] === 'Doctoral consortium') echo 'selected'; ?>>Doctoral consortium</option>
                <option value="Tips, techniques & courseware" <?php if ($submission['paper_type'] === 'Tips, techniques & courseware') echo 'selected'; ?>>Tips, techniques & courseware</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($submission['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Abstract</label>
            <textarea name="abstract" class="form-control" rows="5" required><?php echo htmlspecialchars($submission['abstract']); ?></textarea>
        </div>

        <button type="submit" name="finalise_update_submission" class="btn btn-primary">Update</button>
    </form>
</div>
</body>
</html>