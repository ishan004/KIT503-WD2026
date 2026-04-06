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
        u.Name AS author_name,
        u.Email AS author_email,
        u.affiliation
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

$reviewSql = "
    SELECT
        reviewer.Name AS reviewer_name,
        r.results
    FROM Review r
    JOIN Users reviewer ON r.user_id = reviewer.id
    WHERE r.submission_id = :id
";
$reviewStmt = $pdo->prepare($reviewSql);
$reviewStmt->bindParam(':id', $id, PDO::PARAM_INT);
$reviewStmt->execute();
$reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);

$showAbstract = isset($_GET['show']) && $_GET['show'] === 'abstract';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper Details</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Paper Details</h1>
    <a href="submissions.php" class="btn btn-secondary mb-3">Back</a>

    <div class="card p-3 mb-3">
        <h3><?php echo htmlspecialchars($submission['title']); ?></h3>
        <p><strong>Author:</strong> <?php echo htmlspecialchars($submission['author_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($submission['author_email']); ?></p>
        <p><strong>Affiliation:</strong> <?php echo htmlspecialchars($submission['affiliation']); ?></p>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($submission['paper_type']); ?></p>

        <a href="details.php?id=<?php echo $submission['id']; ?>&show=abstract" class="btn btn-info btn-sm mb-3">
            View abstract
        </a>

        <?php if ($showAbstract): ?>
            <div class="alert alert-light border">
                <strong>Abstract:</strong><br>
                <?php echo nl2br(htmlspecialchars($submission['abstract'])); ?>
            </div>
        <?php endif; ?>

        <h4>Reviews</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Reviewer</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($reviews) > 0): ?>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['reviewer_name']); ?></td>
                        <td><?php echo htmlspecialchars($review['results']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No reviews available.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>