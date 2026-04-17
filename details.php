<?php
session_start();
require_once 'db_conn.php';
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

$flash = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php require_once 'nav.php'; ?>

<div class="content-area">
  <div class="wrapper">
    <div class="details-box">
      <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
        <h2>Paper Details</h2>
        <a href="submissions.php" class="secondary-btn">Back</a>
      </div>

      <?php if ($flash): ?>
        <div class="alert-box alert-success">
          <?php echo htmlspecialchars($flash); ?>
        </div>
      <?php endif; ?>

      <div class="detail-card">
        <h3><?php echo htmlspecialchars($submission['title']); ?></h3>
        <p><strong>Author:</strong> <?php echo htmlspecialchars($submission['author_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($submission['author_email']); ?></p>
        <p><strong>Affiliation:</strong> <?php echo htmlspecialchars($submission['affiliation']); ?></p>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($submission['paper_type']); ?></p>

        <button id="toggleAbstractBtn" class="detailsBtn" type="button">View Abstract</button>

        <div id="abstractBox" class="abstract-box hidden">
          <strong>Abstract:</strong><br>
          <?php echo nl2br(htmlspecialchars($submission['abstract'])); ?>
        </div>

        <h3 style="margin-top:24px;">Reviews</h3>
        <table class="submission-table review-table">
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
  </div>
</div>

<footer>
  <div class="container footer-row">
    <p>&copy; 2026 Conference Management System</p>
  </div>
</footer>

<script src="script.js"></script>
</body>
</html>