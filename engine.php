<?php
include('db_conn.php');

/* CREATE */
if (isset($_POST['create_submission'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $affiliation = trim($_POST['affiliation']);
    $paper_type = trim($_POST['paper_type']);
    $title = trim($_POST['title']);
    $abstract = trim($_POST['abstract']);

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $insertUser = $pdo->prepare("
            INSERT INTO Users (Name, Email, affiliation, Role)
            VALUES (:name, :email, :affiliation, 'Author')
        ");
        $insertUser->bindParam(':name', $name);
        $insertUser->bindParam(':email', $email);
        $insertUser->bindParam(':affiliation', $affiliation);
        $insertUser->execute();

        $user_id = $pdo->lastInsertId();
    } else {
        $user_id = $user['id'];

        $checkSubmission = $pdo->prepare("SELECT * FROM Submissions WHERE User_id = :user_id");
        $checkSubmission->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $checkSubmission->execute();
        $existingSubmission = $checkSubmission->fetch(PDO::FETCH_ASSOC);

        if ($existingSubmission) {
            echo "<script>alert('This author has already submitted a paper.'); window.location='details.php?id=" . $existingSubmission['id'] . "';</script>";
            exit;
        }
    }

    $insertSubmission = $pdo->prepare("
        INSERT INTO Submissions (User_id, title, paper_type, accepted, abstract)
        VALUES (:user_id, :title, :paper_type, 0, :abstract)
    ");
    $insertSubmission->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $insertSubmission->bindParam(':title', $title);
    $insertSubmission->bindParam(':paper_type', $paper_type);
    $insertSubmission->bindParam(':abstract', $abstract);

    if ($insertSubmission->execute()) {
        echo "<script>alert('Your paper has been added.'); window.location='submissions.php';</script>";
    } else {
        echo "<script>alert('Error adding paper.'); window.location='create_submission.php';</script>";
    }
    exit;
}

/* UPDATE */
if (isset($_POST['finalise_update_submission'])) {
    $submission_id = (int)$_POST['submission_id'];
    $paper_type = trim($_POST['paper_type']);
    $title = trim($_POST['title']);
    $abstract = trim($_POST['abstract']);

    $updateStmt = $pdo->prepare("
        UPDATE Submissions
        SET paper_type = :paper_type,
            title = :title,
            abstract = :abstract
        WHERE id = :id
    ");
    $updateStmt->bindParam(':paper_type', $paper_type);
    $updateStmt->bindParam(':title', $title);
    $updateStmt->bindParam(':abstract', $abstract);
    $updateStmt->bindParam(':id', $submission_id, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        echo "<script>alert('Submission has been edited.'); window.location='submissions.php';</script>";
    } else {
        echo "<script>alert('Error updating submission.'); window.location='update_submission.php?id=$submission_id';</script>";
    }
    exit;
}

/* DELETE */
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    $deleteReviews = $pdo->prepare("DELETE FROM Review WHERE submission_id = :id");
    $deleteReviews->bindParam(':id', $delete_id, PDO::PARAM_INT);
    $deleteReviews->execute();

    $deleteSubmission = $pdo->prepare("DELETE FROM Submissions WHERE id = :id");
    $deleteSubmission->bindParam(':id', $delete_id, PDO::PARAM_INT);

    if ($deleteSubmission->execute()) {
        echo "<script>alert('Submission has been deleted.'); window.location='submissions.php';</script>";
    } else {
        echo "<script>alert('Error deleting submission.'); window.location='submissions.php';</script>";
    }
    exit;
}
?>