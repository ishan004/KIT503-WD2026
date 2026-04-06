<?php
session_start();
include('db_conn.php');

function redirect_with_message(string $location, string $message): void {
    $_SESSION['flash_message'] = $message;
    header("Location: $location");
    exit;
}

/* CREATE */
if (isset($_POST['create_submission'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $affiliation = trim($_POST['affiliation'] ?? '');
    $paper_type = trim($_POST['paper_type'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $abstract = trim($_POST['abstract'] ?? '');

    if ($name === '' || $email === '' || $affiliation === '' || $paper_type === '' || $title === '' || $abstract === '') {
        redirect_with_message('create_submissions.php', 'Please complete all fields.');
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            $insertUser = $pdo->prepare("
                INSERT INTO Users (Name, Email, affiliation, Role)
                VALUES (:name, :email, :affiliation, 'Author')
            ");
            $insertUser->execute([
                ':name' => $name,
                ':email' => $email,
                ':affiliation' => $affiliation
            ]);

            $user_id = (int)$pdo->lastInsertId();
        } else {
            $user_id = (int)$user['id'];

            $checkSubmission = $pdo->prepare("SELECT * FROM Submissions WHERE User_id = :user_id LIMIT 1");
            $checkSubmission->execute([':user_id' => $user_id]);
            $existingSubmission = $checkSubmission->fetch();

            if ($existingSubmission) {
                $pdo->rollBack();
                redirect_with_message(
                    'details.php?id=' . $existingSubmission['id'],
                    'This author has already submitted a paper.'
                );
            }
        }

        $insertSubmission = $pdo->prepare("
            INSERT INTO Submissions (User_id, title, paper_type, accepted, abstract)
            VALUES (:user_id, :title, :paper_type, 0, :abstract)
        ");

        $insertSubmission->execute([
            ':user_id' => $user_id,
            ':title' => $title,
            ':paper_type' => $paper_type,
            ':abstract' => $abstract
        ]);

        $pdo->commit();
        redirect_with_message('submissions.php', 'Your paper has been added.');
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        redirect_with_message('create_submissions.php', 'Error adding paper.');
    }
}

/* UPDATE */
if (isset($_POST['finalise_update_submission'])) {
    $submission_id = (int)($_POST['submission_id'] ?? 0);
    $paper_type = trim($_POST['paper_type'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $abstract = trim($_POST['abstract'] ?? '');

    if ($submission_id <= 0 || $paper_type === '' || $title === '' || $abstract === '') {
        redirect_with_message('submissions.php', 'Invalid update request.');
    }

    $updateStmt = $pdo->prepare("
        UPDATE Submissions
        SET paper_type = :paper_type,
            title = :title,
            abstract = :abstract
        WHERE id = :id
    ");

    $ok = $updateStmt->execute([
        ':paper_type' => $paper_type,
        ':title' => $title,
        ':abstract' => $abstract,
        ':id' => $submission_id
    ]);

    if ($ok) {
        redirect_with_message('submissions.php', 'Submission has been edited.');
    } else {
        redirect_with_message('update_submission.php?id=' . $submission_id, 'Error updating submission.');
    }
}

/* DELETE */
if (isset($_POST['delete_submission'])) {
    $delete_id = (int)($_POST['delete_id'] ?? 0);

    if ($delete_id <= 0) {
        redirect_with_message('submissions.php', 'Invalid delete request.');
    }

    try {
        $pdo->beginTransaction();

        $deleteReviews = $pdo->prepare("DELETE FROM Review WHERE submission_id = :id");
        $deleteReviews->execute([':id' => $delete_id]);

        $deleteSubmission = $pdo->prepare("DELETE FROM Submissions WHERE id = :id");
        $deleteSubmission->execute([':id' => $delete_id]);

        $pdo->commit();
        redirect_with_message('submissions.php', 'Submission has been deleted.');
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        redirect_with_message('submissions.php', 'Error deleting submission.');
    }
}

redirect_with_message('submissions.php', 'Invalid request.');