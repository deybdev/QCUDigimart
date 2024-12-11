<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <style>

    </style>
</head>
<body>
    <?php include '../main/header.php'; ?>

    <div class="reports-container">
        <h1>Your Reports</h1>

        <?php
        include '../config/config.php';
        if (!isset($_SESSION['customer_id'])) {
            echo '<p>Please log in to view your reports.</p>';
            exit;
        }
        $customer_id = $_SESSION['customer_id'];
        $query = "SELECT * FROM reports WHERE reporter_id = $customer_id ORDER BY date_reported DESC";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while ($report = $result->fetch_assoc()) {
                // Determine the status class based on the report's status
                $status_class = strtolower($report['status']);
                if ($status_class == 'ignored') {
                    $status_class = 'ignored';  // Apply the ignored class
                }
                echo '<div class="report-card">';
                echo '<div class="report-header">';
                echo '<h2>Report ID : ' . $report['id'] . '</h2>';
                echo '<span class="report-status status-' . $status_class . '">' . $report['status'] . '</span>';
                echo '</div>';
                echo '<div class="report-details">';
                echo '<p><strong>Type :</strong> ' . $report['report_type'] . '</p>';
                echo '<p><strong>Reason :</strong> ' . $report['reason'] . '</p>';
                echo '<p><strong>Description :</strong> ' . $report['description'] . '</p>';
                echo '<p><strong>Date Reported :</strong> ' . $report['date_reported'] . '</p>';
                if (!empty($report['proof'])) {
                    echo '<div class="report-proof">';
                    echo '<p><strong>Proof :</strong> <a href="' . htmlspecialchars($report['proof']) . '" target="_blank">View Proof</a></p>';
                    echo '</div>';
                }

                // Display admin comment if status is resolved
                if (strtolower($report['status']) === 'resolved' && !empty($report['admin_comment'])) {
                    echo '<div class="admin-comment">';
                    echo '<strong>Comment:</strong>';
                    echo '<p>' . $report['admin_comment'] . '</p>';
                    echo '</div>';
                }

                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No reports found.</p>';
        }
        ?>
    </div>


    <?php include '../main/footer.php'; ?>
</body>
</html>
