<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
    $phone = htmlspecialchars(strip_tags(trim($_POST['phone'])));
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

    // File upload handling
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['document']['tmp_name'];
        $fileName = $_FILES['document']['name'];
        $fileSize = $_FILES['document']['size'];
        $fileType = $_FILES['document']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Define allowed file extensions
        $allowedFileExtensions = array('pdf', 'doc', 'docx', 'jpg', 'png');

        if (in_array($fileExtension, $allowedFileExtensions)) {
            $uploadFileDir = './uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            $newFileName = time() . "_" . $fileName;
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $fileUploadMessage = "File uploaded successfully.";
            } else {
                $fileUploadMessage = "There was an error uploading your file.";
            }
        } else {
            $fileUploadMessage = "Invalid file type. Allowed types: " . implode(", ", $allowedFileExtensions);
        }
    } else {
        $fileUploadMessage = "No file uploaded.";
    }

    // Example email sending (optional)
    $to = "callumdewar44@gmail.com"; // Replace with your email address
    $subject = "New Contact Form Submission";
    $body = "Name: $name\nEmail: $email\nPhone: $phone\nMessage:\n$message";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        $emailStatus = "Email sent successfully.";
    } else {
        $emailStatus = "Failed to send email.";
    }

    // Display feedback to the user
    echo "<p>Thank you, $name! Your message has been received.</p>";
    echo "<p>$fileUploadMessage</p>";
    echo "<p>$emailStatus</p>";
} else {
    echo "<p>Invalid request.</p>";
}
?>
