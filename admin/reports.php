<?php
session_start();
include '../config/config.php'; // Include your database connection

$query = "SELECT r.id, r.reporter_id, r.target_id, r.report_type, r.reason, r.description, r.proof, r.date_reported, 
       r.status, reporter.first_name AS reporter_name, reporter.last_name AS reporter_lastname, target.store_name AS target_name 
FROM reports r 
LEFT JOIN customer reporter ON r.reporter_id = reporter.id 
LEFT JOIN seller target ON r.target_id = target.id;
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            overflow: auto;
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 25px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .comment-modal{
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .resolved-report .resolve-button,
        .resolved-report .ignore-button,
        .ignored-report .resolve-button,
        .ignored-report .ignore-button {
            display: none;
        }
        
        .report-status {
            font-weight: bold;
            color: #fff;
            padding: 5px;
            text-transform: uppercase;
            border-radius: 3px;
        }
        
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="wrapper">
        <h1>Reports</h1>
        <div class="accounts-table-container reports-tab">
            <table id="default-table">
                <tr>
                    <th>ID</th>
                    <th>Reporter</th>
                    <th>Report Type</th>
                    <th>Target</th>
                    <th>Description</th>
                    <th>Proof</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr class="<?php 
                        echo ($row['status'] == 'resolved' ? 'resolved-report' : 
                               ($row['status'] == 'ignored' ? 'ignored-report' : '')); 
                    ?>">
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td>
                            <?php
                            $reporter_name = !empty($row['reporter_name']) ? 
                                htmlspecialchars($row['reporter_name']) . ' ' . 
                                htmlspecialchars($row['reporter_lastname']) : 
                                'Unknown Reporter';
                            echo $reporter_name;
                            ?>
                        </td>

                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                        <td><?php echo htmlspecialchars($row['target_name']); ?></td>
                        <td>
                            <button class="show-description" data-description="<?php echo htmlspecialchars($row['description']); ?>">
                                View Description
                            </button>
                        </td>
                        <td><a href="<?php echo htmlspecialchars($row['proof']); ?>" target="_blank">View Proof</a></td>
                        <td>
                            <?php if ($row['status'] == 'resolved'): ?>
                                <span class="report-status report-status-resolved">Resolved</span>
                            <?php elseif ($row['status'] == 'ignored'): ?>
                                <span class="report-status report-status-ignored">Ignored</span>
                            <?php else: ?>
                                <span class="report-status">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="resolve-button" data-report-id="<?php echo $row['id']; ?>">Resolve</button>
                            <button class="ignore-button">Ignore</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

     <!-- Modal for Admin Comment -->
    <div id="commentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Resolve Report</h2>
            <form id="resolveForm" action="functions/resolve_report.php" method="POST" >
                <div class="comment-modal">
                    <input type="hidden" id="report_id" name="report_id">
                    <label for="admin_comment">Add a Comment</label>
                    <textarea id="admin_comment" name="admin_comment" required></textarea>
                </div>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>


    <!-- Modal for Description -->
    <div id="descriptionModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Description</h2>
            <p id="modal-description"></p>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("descriptionModal");

        // Get all buttons that open the modal
        var buttons = document.querySelectorAll('.show-description');

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal and show the description
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                var description = button.getAttribute('data-description');
                document.getElementById("modal-description").innerText = description;
                modal.style.display = "block";
            });
        });

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Resolve button script
        var resolveButtons = document.querySelectorAll('button.resolve-button');

        resolveButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var reportId = button.getAttribute('data-report-id');
                document.getElementById("report_id").value = reportId;
                commentModal.style.display = "block";
            });
        });

        // Resolve form submission
        document.getElementById('resolveForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('functions/resolve_report.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Report successfully resolved!');
                    location.reload();
                } else {
                    alert('Error resolving the report. Please try again.');
                }
            })
            .catch(error => {
                alert('An error occurred. Please try again later.');
            });
        });

        // Ignore button script
        var ignoreButtons = document.querySelectorAll('button.ignore-button');

        ignoreButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var reportId = this.closest('tr').querySelector('.resolve-button').getAttribute('data-report-id');
                
                if (confirm('Are you sure you want to ignore this report?')) {
                    fetch('functions/ignore_report.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'report_id=' + reportId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Report successfully ignored!');
                            location.reload();
                        } else {
                            alert('Error ignoring the report. Please try again.');
                        }
                    })
                    .catch(error => {
                        alert('An error occurred. Please try again later.');
                    });
                }
            });
        });
    </script>
</body>
</html>