<!-- DISPLAY PENDING SELLERS -->
<?php 
include '../config/config.php';

$rejected_account_sql = "SELECT COUNT(*) AS rejected_count FROM rejected_sellers";
$rejected_account_count_result = $conn->query($rejected_account_sql);
$rejected_account_count = $rejected_account_count_result->fetch_assoc()['rejected_count'];


// Query to select pending sellers
$sql = "SELECT id, first_name, last_name, store_name, email FROM pending_sellers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Sellers</title>
</head>

<body>
<?php include 'sidebar.php'; ?>

<div class="wrapper">
    <h1>Pending Sellers</h1>
    <div class="filter-report">
        <p>Sort by: </p>
        <div class="sort-accounts">
            <select name="user_type" id="user_type">
                <option value="all" selected>All</option>
                <option value="date">Date</option>
                <option value="name">Name</option>
            </select>
        </div>
    </div>

    <div class="sellers-container">
        <div class="sellers-table">
            <table id="default-table" class="seller">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Store Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php
                $auto_increment_id = 1;
                // Check if there are results
                if ($result && $result->num_rows > 0) {
                    // Loop through each row and display data
                    while ($row = $result->fetch_assoc()) {
                        $approveFormId = "approveForm" . $row['id'];
                        $rejectFormId = "rejectForm" . $row['id'];
                        
                        echo "<tr>";
                        echo "<td>" . $auto_increment_id . "</td>";
                        echo "<td>" . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['store_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>
                                <button onclick='openApproveModal(" . $row['id'] . ")'>Approve</button>
                                <button onclick='openRejectModal(" . $row['id'] . ")'>Reject</button>
                              </td>";
                        echo "</tr>";
                        $auto_increment_id++;
                    }
                } else {
                    // If no results, show message
                    echo "<tr><td colspan='5'>No pending sellers found</td></tr>";
                }
                $conn->close();
                ?>
            </table>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="flagged-count">
                <div class="flagged-info">    
                    <p>Rejected Sellers:</p>
                    <h2><?php echo $rejected_account_count; ?></h2> <!-- This count could be dynamically generated -->
                </div>
            </div>

            <!-- Rejected Sellers Section -->
            <div class="recent-reports-container">
                <h3>Rejected Sellers</h3>
                
                <?php
                    include '../config/config.php';
                    // Query to retrieve rejected sellers data
                    $sql = "SELECT id, name, date_rejected FROM rejected_sellers ORDER BY date_rejected DESC";
                    $result = $conn->query($sql);

                    if ($result) {  // Check if query execution was successful
                        if ($result->num_rows > 0) {
                            // Loop through each rejected seller record and display it
                            while ($row = $result->fetch_assoc()) {
                                $date_rejected = $row["date_rejected"];
                                $date = new DateTime($date_rejected);
                                $formatted_date = $date->format('m/d/Y - g:i a');
                                echo "
                                    <div class='recent-box'>
                                        <div class='recent-info'>
                                            <h4>" . htmlspecialchars($row['name']) . "</h4>
                                            <p>" . $formatted_date . "</p>
                                        </div>
                                    </div>
                                    <hr>
                                ";
                            }
                        } else {
                            echo "<p>No rejected sellers found.</p>";
                        }
                    } else {
                        // Display an error message if the query fails
                        echo "<p>Error retrieving rejected sellers: " . $conn->error . "</p>";
                    }

                    $conn->close();
                ?>


            </div>

        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="modal">
    <div class="modal-content">
        <p>Are you sure that you want to <strong>APPROVE</strong> this user as a seller?</p>
        <button id="confirmApprove" onclick="confirmAction('approve')">Yes</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <p>Are you sure that you want to <strong>REJECT</strong> this user as a seller?</p>
        <button id="confirmReject" onclick="confirmAction('reject')">Yes</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
</div>

<script>
    
let selectedSellerId = null;
let actionType = '';

// Function to open Approve modal
function openApproveModal(sellerId) {
    selectedSellerId = sellerId;
    actionType = 'approve';
    document.getElementById('approveModal').style.display = 'flex';
}

// Function to open Reject modal
function openRejectModal(sellerId) {
    selectedSellerId = sellerId;
    actionType = 'reject';
    document.getElementById('rejectModal').style.display = 'flex';
}

// Function to close all modals
function closeModal() {
    document.getElementById('approveModal').style.display = 'none';
    document.getElementById('rejectModal').style.display = 'none';
}

// Function to confirm action
function confirmAction(type) {
    const form = document.createElement('form');
    form.method = 'post';
    form.action = type === 'approve' ? 'functions/approve_sellers.php' : 'functions/reject_sellers.php';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'seller_id';
    input.value = selectedSellerId;
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const approveModal = document.getElementById('approveModal');
    const rejectModal = document.getElementById('rejectModal');

    if (event.target === approveModal) {
        approveModal.style.display = 'none';
    } else if (event.target === rejectModal) {
        rejectModal.style.display = 'none';
    }
};
</script>

</body>
</html>
