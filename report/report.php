<?php
$product_name = $product_image = $seller_name = $seller_profile = $heading = "";
include "../config/config.php"; // Adjust path if needed

// Ensure the session is started
session_start();

// Function to add a report
function addReport($conn, $reporter_id, $target_id, $report_type, $reason, $description, $proof = null)
{
    $query = "INSERT INTO reports (reporter_id, target_id, report_type, reason, description, proof) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iissss", $reporter_id, $target_id, $report_type, $reason, $description, $proof);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return false;
}

// Check if the customer is logged in and set the reporter_id from the session
if (isset($_SESSION['customer_id'])) {
    $reporter_id = $_SESSION['customer_id']; // Set the reporter_id from session (customer's ID)
} else {
    // If not logged in, handle this as needed (e.g., redirect or show error)
    echo "<script>alert('You must be logged in to report.'); window.location.href = '../login/login.php';</script>";
    exit;
}

// Check if 'seller_id' or 'product_id' is set in the URL parameters
if (isset($_GET['seller_id'])) {
    $seller_name = htmlspecialchars($_GET['seller_name']);
    $seller_id = intval($_GET['seller_id']);

    // Query to get the store profile from the database
    $query = "SELECT store_profile FROM seller WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $seller_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $seller_profile = $row['store_profile'];
        }
        $stmt->close();
    }
    $heading = "Report this Seller";
} elseif (isset($_GET['product_id'])) {
    $product_name = htmlspecialchars($_GET['product_name']);
    $product_image = htmlspecialchars($_GET['product_image']);
    $product_id = intval($_GET['product_id']);
    $heading = "Report this Product";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_report'])) {
    $target_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : intval($_POST['seller_id']);
    $report_type = isset($_POST['product_id']) ? 'product' : 'seller';
    $reason = htmlspecialchars($_POST['reason']);
    $description = htmlspecialchars($_POST['description']);
    $proof = null;

    // Handle file upload
    if (!empty($_FILES['proof']['name'])) {
        $proof_dir = "../assets/reports/";
        $proof_file = $proof_dir . basename($_FILES["proof"]["name"]);
        if (move_uploaded_file($_FILES["proof"]["tmp_name"], $proof_file)) {
            $proof = $proof_file;
        }
    }

    // Add report to the database
    if (addReport($conn, $reporter_id, $target_id, $report_type, $reason, $description, $proof)) {
        echo "<script>alert('Report submitted successfully.');</script>";
        header("Location: ../main/main-home.php");
    } else {
        echo "<script>alert('Failed to submit the report. Please try again.');</script>";
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Form</title>
</head>
<body>
    <?php include "../main/header.php"; ?>

    <div class="form-container">
        <h2 style="text-align: center; margin-top: 20px;"><?php echo $heading; ?></h2>
        <div class="form-wrapper">
            <div class="form-box">
                <form action="report.php" method="post" enctype="multipart/form-data">
                    <div class="form">
                        <!-- Display product or seller details -->
                        <?php if (!empty($product_name)): ?>
                            <div class="report-info">
                                <h3><?php echo $product_name; ?></h3>
                                <div class="prod-img">
                                    <img src="<?php echo $product_image; ?>" alt="Product Image">
                                </div>
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            </div>
                        <?php elseif (!empty($seller_name)): ?>
                            <div class="report-info">
                                <h3><?php echo $seller_name; ?></h3>
                                <!-- Display store profile image if available -->
                                <?php if (!empty($seller_profile)): ?>
                                    <div class="prod-img">
                                        <img src="<?php echo $seller_profile; ?>" alt="Store Profile">
                                    </div>
                                <?php endif; ?>
                                <input type="hidden" name="seller_id" value="<?php echo $seller_id; ?>">
                            </div>
                        <?php endif; ?>

                        <!-- Reason for the report -->
                        <div class="form-element full-width">
                            <label for="reason">Reason</label>
                            <select name="reason" id="reason" required>
                                <option value="" disabled selected>Select a reason</option>
                                <option value="hazardous_product">Hazardous Product</option>
                                <option value="violent_graphic_content">Violent and Graphic Content</option>
                                <option value="scam_fraud">Scam, Fraud, or False Information</option>
                                <option value="offensive_content">Inappropriate or Offensive Content</option>
                                <option value="copyright_infringement">Copyright or Trademark Infringement</option>
                                <option value="counterfeit_product">Counterfeit Product</option>
                                <option value="misleading_description">Misleading Product Description</option>
                                <option value="defective_product">Defective Product</option>
                                <option value="unauthorized_resale">Unauthorized Resale</option>
                                <option value="policy_violation">Violation of Policies or Guidelines</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- 'Other Reason' input field -->
                        <div class="form-element full-width" id="other-reason-wrapper" style="display: none;">
                            <label for="other-reason">Other Reason</label>
                            <input type="text" id="other-reason" name="other_reason" placeholder="Specify your reason">
                        </div>

                        <!-- Description -->
                        <div class="form-element full-width">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" placeholder="Provide more details..." required></textarea>
                        </div>

                        <!-- Proof upload -->
                        <div class="form-element full-width">
                            <label for="proof" id="proof-label">Upload Proof (Optional)</label>
                            <input type="file" id="proof" name="proof">
                        </div>

                        <!-- Submit button -->
                        <div class="form-element">
                            <button class="btn" type="submit" name="submit_report">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "../main/footer.php"; ?>

    <script>
        // Toggle the 'Other Reason' field visibility
        function toggleOtherReason() {
            const reasonSelect = document.getElementById("reason");
            const otherReasonInput = document.getElementById("other-reason-wrapper");
            otherReasonInput.style.display = reasonSelect.value === "other" ? "block" : "none";
        }

        // Initialize the toggle functionality
        window.onload = function() {
            const reasonSelect = document.getElementById("reason");
            reasonSelect.addEventListener("change", toggleOtherReason);
            toggleOtherReason(); // Ensure correct initial state
        };
    </script>
</body>
</html>
