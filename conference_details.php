<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Details</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <?php require_once 'nav.php'; ?>

    <div class="content-area">
        <div class="wrapper">
            <div class="details-box">
                <h2 class="text-center mb-4">Conference Details</h2>

                <div class="alert alert-info text-center fw-bold">
                    Conference Date: 6–8 July 2026
                </div>

                <h3 class="mt-4">Submission Deadlines and Page Limits</h3>
                <table class="table table-bordered mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>Type</th>
                            <th>Pages</th>
                            <th>Deadline</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Paper</td>
                            <td>6 pages plus 1 page for references if required</td>
                            <td>1 March</td>
                        </tr>
                        <tr>
                            <td>Working group</td>
                            <td>2 pages</td>
                            <td>1 March</td>
                        </tr>
                        <tr>
                            <td>Poster</td>
                            <td>1 page</td>
                            <td>15 April</td>
                        </tr>
                        <tr>
                            <td>Doctoral consortium</td>
                            <td>2 pages</td>
                            <td>15 April</td>
                        </tr>
                        <tr>
                            <td>Tips, techniques &amp; courseware</td>
                            <td>2 pages</td>
                            <td>15 May</td>
                        </tr>
                    </tbody>
                </table>

                <h3 class="mt-5">Contact Address</h3>
                <table class="table table-hover mt-3">
                    <tbody>
                        <tr>
                            <td><strong>Conference Chair</strong></td>
                            <td>ibhusal@utas.edu.au</td>
                        </tr>
                        <tr>
                            <td><strong>Organisation</strong></td>
                            <td>ibhusal004@utas.edu.au</td>
                        </tr>
                        <tr>
                            <td><strong>Supporter Liaison</strong></td>
                            <td>ibhusal12@utas.edu.au</td>
                        </tr>
                    </tbody>
                </table>

                <div class="text-center mt-5">
                    <a href="index.php" class="btn btn-primary px-5">Back to Home</a>
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