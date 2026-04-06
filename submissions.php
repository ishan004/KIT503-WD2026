<?php
session_start();
include('db_conn.php');

$search = trim($_GET['search'] ?? '');
$type = trim($_GET['type'] ?? 'all');

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
    WHERE 1 = 1
";

$params = [];

if ($type !== 'all') {
    $sql .= " AND s.paper_type = :type";
    $params[':type'] = $type;
}

if ($search !== '') {
    $sql .= " AND (s.title LIKE :search OR u.Name LIKE :search)";
    $params[':search'] = "%$search%";
}

$sql .= " ORDER BY s.id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$flash = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Paper Submissions</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <nav class="navbar">
        <div class="container">
            <a class="brand" href="index.php">Conference Management System</a>

            <div class="nav-buttons">
                <a class="btn btn-outline nav-link" href="registration.php">Registration</a>
                <a class="btn btn-outline nav-link" href="submissions.php">Submissions</a>
                <a class="btn btn-outline nav-link" href="conference_details.php">Conference Details</a>
            </div>
        </div>
    </nav>

  <main class="content-area">
    <div class="wrapper">
      <div class="details-box">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
          <h2>Paper Submissions</h2>
          <a href="create_submissions.php" class="success-btn">Add Submission</a>
        </div>

        <?php if ($flash): ?>
          <div class="alert-box alert-success">
            <?php echo htmlspecialchars($flash); ?>
          </div>
        <?php endif; ?>

        <form method="get" class="filter-box">
          <div>
            <label for="typeFilter">Filter by type</label><br>
            <select id="typeFilter" name="type">
              <option value="all" <?php if ($type === 'all') echo 'selected'; ?>>All</option>
              <option value="Paper" <?php if ($type === 'Paper') echo 'selected'; ?>>Paper</option>
              <option value="Working group" <?php if ($type === 'Working group') echo 'selected'; ?>>Working group</option>
              <option value="Poster" <?php if ($type === 'Poster') echo 'selected'; ?>>Poster</option>
              <option value="Doctoral consortium" <?php if ($type === 'Doctoral consortium') echo 'selected'; ?>>Doctoral consortium</option>
              <option value="Tips, techniques & courseware" <?php if ($type === 'Tips, techniques & courseware') echo 'selected'; ?>>Tips, techniques & courseware</option>
            </select>
          </div>

          <div>
            <label for="searchInput">Search</label><br>
            <input
              type="text"
              id="searchInput"
              name="search"
              placeholder="Search by title or author"
              value="<?php echo htmlspecialchars($search); ?>"
            />
          </div>

          <div class="filter-buttons">
            <button type="submit" class="secondary-btn">Search</button>
            <a href="submissions.php" class="detailsBtn">Clear</a>
          </div>
        </form>

        <div class="table-wrap">
          <table class="submission-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Type</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($submissions) > 0): ?>
                <?php foreach ($submissions as $row): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['paper_type']); ?></td>
                    <td class="actions-cell">
                      <a href="details.php?id=<?php echo $row['id']; ?>" class="detailsBtn">View Details</a>
                      <a href="update_submission.php?id=<?php echo $row['id']; ?>" class="warning-btn">Edit</a>

                      <form action="engine.php" method="post" class="delete-form" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_submission" class="danger-btn">Delete</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4">No matching submissions found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
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