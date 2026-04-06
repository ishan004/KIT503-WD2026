<?php
include('db_conn.php');

$search = $_GET['search'] ?? '';
$type = $_GET['type'] ?? 'all';

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

if (!empty($search)) {
    $sql .= " AND (s.title LIKE :search OR u.Name LIKE :search)";
    $params[':search'] = "%$search%";
}

$sql .= " ORDER BY s.id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Submission List</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <nav class="navbar">
    <div class="container">
      <a class="brand" href="index.html">Conference Management System</a>
      <div class="nav-buttons">
        <a href="registration.html" class="btn-outline nav-link">Registration</a>
        <a href="submissions.php" class="btn-outline nav-link active">Submissions</a>
        <a href="details.php" class="btn-outline nav-link">Details</a>
      </div>
    </div>
  </nav>

  <main class="content-area">
    <div class="wrapper">
      <div class="details-box">
        <h2>Paper Submissions</h2>

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
            <button type="submit">Search</button>
            <a href="submissions.php" class="detailsBtn" style="text-decoration:none;">Clear</a>
          </div>
        </form>

        <div id="submissionList">
          <?php if (count($submissions) > 0): ?>
            <?php foreach ($submissions as $row): ?>
              <div class="submission-box">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author_name']); ?></p>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($row['paper_type']); ?></p>

                <button class="detailsBtn" onclick="toggleDetails('details-<?php echo $row['id']; ?>')">
                  View Details
                </button>

                <a href="update_submission.php?id=<?php echo $row['id']; ?>" class="detailsBtn" style="text-decoration:none;">
                  Edit
                </a>

                <a
                  href="engine.php?delete_id=<?php echo $row['id']; ?>"
                  class="detailsBtn"
                  style="text-decoration:none;"
                  onclick="return confirm('Are you sure you want to delete this submission?');"
                >
                  Delete
                </a>

                <div class="more-info" id="details-<?php echo $row['id']; ?>">
                  <p><strong>Title:</strong> <?php echo htmlspecialchars($row['title']); ?></p>
                  <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author_name']); ?></p>
                  <p><strong>Email:</strong> <?php echo htmlspecialchars($row['author_email']); ?></p>
                  <p><strong>Affiliation:</strong> <?php echo htmlspecialchars($row['affiliation']); ?></p>
                  <p><strong>Type:</strong> <?php echo htmlspecialchars($row['paper_type']); ?></p>
                  <p><strong>Abstract:</strong> <?php echo htmlspecialchars($row['abstract']); ?></p>
                  <p>
                    <a href="details.php?id=<?php echo $row['id']; ?>" class="detailsBtn" style="text-decoration:none;">
                      Open Full Details
                    </a>
                  </p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p id="noResults" style="display:block;">No matching submissions found.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>&copy; 2026 Conference Management System</p>
    </div>
  </footer>

  <script>
    function toggleDetails(id) {
      const details = document.getElementById(id);
      if (details.style.display === "block") {
        details.style.display = "none";
      } else {
        details.style.display = "block";
      }
    }
  </script>
</body>
</html>