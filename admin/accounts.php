<?php 
include '../config/config.php';

// Get sorting and filtering options from the URL
$sort_by = $_GET['sort_by'] ?? 'all';
$filter_by = $_GET['filter_by'] ?? 'all';

// Determine the order_by clause
$order_by = "id"; // Default sorting
if ($sort_by === 'name') {
    $order_by = "first_name, last_name";
} elseif ($sort_by === 'date') {
    $order_by = "date_created DESC";
}

// Apply the filter condition
$filter_condition = "";
if ($filter_by === 'customer') {
    $filter_condition = "WHERE user_type = 'Customer'";
} elseif ($filter_by === 'seller') {
    $filter_condition = "WHERE user_type = 'Seller'";
}

// Construct the SQL query
$query = "
SELECT * FROM (
    SELECT id, first_name, last_name, profile_image, email, date_created, 'Customer' AS user_type, NULL AS store_name, NULL AS store_profile, status, suspend_until
    FROM customer
    UNION ALL
    SELECT id, first_name, last_name, NULL AS profile_image, email, date_created, 'Seller' AS user_type, store_name, store_profile, status, suspend_until
    FROM seller
) AS combined
$filter_condition
ORDER BY $order_by
";

// Execute the query
$result = $conn->query($query);

// Check for query execution issues
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">    
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="wrapper">
    <div class="accounts-table">
        <div class="header">
            <h1>Accounts</h1>
            <div class="filter-table-row">
                <p>Sort by: </p>
                <div class="sort-accounts">
                    <select name="sort_by" id="sort_by">
                        <option value="all" <?= $sort_by === 'all' ? 'selected' : '' ?>>All</option>
                        <option value="name" <?= $sort_by === 'name' ? 'selected' : '' ?>>Name</option>
                        <option value="date" <?= $sort_by === 'date' ? 'selected' : '' ?>>Date</option>
                    </select>
                </div>
                <p>Filter by: </p>
                <div class="filter-accounts">
                    <select name="filter_by" id="filter_by">
                        <option value="all" <?= $filter_by === 'all' ? 'selected' : '' ?>>All</option>
                        <option value="customer" <?= $filter_by === 'customer' ? 'selected' : '' ?>>Customer</option>
                        <option value="seller" <?= $filter_by === 'seller' ? 'selected' : '' ?>>Seller</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="accounts-table-container">
            <table id="default-table">
                <tr>
                    <th>ID</th>
                    <th>Profile</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>

                <?php 
                $auto_increment_id = 1;

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $date_created = $row["date_created"];
                        $date = new DateTime($date_created);
                        $formatted_date = $date->format('m/d/Y - g:i a'); 

                        // Determine status color and display suspend_until if status is suspended
                        $status = strtolower($row["status"]); // Convert status to lowercase for consistency
                        $statusClass = '';
                        $suspendInfo = '';
                        if ($status === 'active') {
                            $statusClass = 'status-active';
                        } elseif ($status === 'banned') {
                            $statusClass = 'status-red';
                        } elseif ($status === 'suspended') {
                            $statusClass = 'status-blue';
                            if (!empty($row["suspend_until"])) {
                                $suspendDate = new DateTime($row["suspend_until"]);
                                $formattedSuspendDate = $suspendDate->format('m/d/Y - g:i a');
                                $suspendInfo = "Until: $formattedSuspendDate";
                            }
                        } 
                        echo "<tr>";
                        echo "<td>" . $auto_increment_id . "</td>";
                        echo '<td><div class="user-info"><img src="../assets/' . ($row["profile_image"] ? $row["profile_image"] : 'received-default.jpg') . '" alt="profile">';
                        echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']);
                        echo '</div></td>';
                        echo "<td><div class = 'status-column'><span class='status-label {$statusClass}'>" . strtoupper(htmlspecialchars($row["status"])) . "</span></div></td>"; // Status with suspend info
                        echo "<td>" . $formatted_date . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["user_type"]) . "</td>";
                        echo "<td class='table-btn'>
                            <button onclick=\"openBanModal({$row['id']}, '{$row['user_type']}', '{$row['status']}')\">
                                <i class='fa-solid fa-ban'></i>
                            </button>
                            <button onclick=\"openSuspendModal('{$row['last_name']}', {$row['id']}, '{$row['user_type']}', '{$row['status']}')\">
                                <i class='fa-solid fa-hourglass'></i>
                            </button>
                            <button onclick=\"openDeleteModal(
                                {$row['id']}, 
                                '{$row['user_type']}', 
                                '" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "' 
                            )\">
                                <i class='fa-solid fa-trash'></i>
                            </button>
                        </td>";
                        echo "</tr>";
                        $auto_increment_id++;
                    }
                } else {
                    echo "<tr><td colspan='7'>No data found</td></tr>"; // Update colspan to match the new column count
                }
                $conn->close();
                ?>



            </table>
        </div>
    </div>
</div>

<!-- Suspension Modal -->
<div id="suspendModal" class="modal">
    <div class="modal-content">
        <h2 id="modalTitle">Suspend User</h2>
        <span id="suspendUserName" data-user-id="" data-user-type=""></span>
            <div class="suspend-dur">
                <label for="suspendDuration" id="durationLabel">Select Duration:</label>
                <input type="date" id="suspendDuration" />
            </div>
        <br>
        <button onclick="suspendAccount()">Confirm</button>
        <button onclick="closeModal('suspendModal')">Cancel</button>
    </div>
</div>


<!-- Ban Modal -->
<div id="banModal" class="modal">
    <div class="modal-content">
        <p>Are you sure that you want to ban this user?</p>
        <br>
        <button onclick="banAccount()">Ban</button>
        <button onclick="closeModal('banModal')">Cancel</button>
    </div>
</div>


<!-- Delete Modal -->
<div id="deleteModal" class="modal" data-user-id="" data-user-type="">
    <div class="modal-content">
        <p>Are you sure that you want to delete <span id="deleteUserName"></span>'s account?</p>
        <br>
        <button onclick="deleteAccount()">Delete</button>
        <button onclick="closeModal('deleteModal')">Cancel</button>
    </div>
</div>

<script>

document.getElementById('sort_by').addEventListener('change', function() {
    const sortBy = this.value;
    const filterBy = document.getElementById('filter_by').value;
    window.location.href = `?sort_by=${sortBy}&filter_by=${filterBy}`;
});

document.getElementById('filter_by').addEventListener('change', function() {
    const filterBy = this.value;
    const sortBy = document.getElementById('sort_by').value;
    window.location.href = `?sort_by=${sortBy}&filter_by=${filterBy}`;
});

// Function to open delete modal
function openDeleteModal(userId, userType, userName) {
    console.log('Opening delete modal with:', { userId, userType, userName }); // Debug log
    
    const deleteModal = document.getElementById('deleteModal');
    const deleteUserName = document.getElementById('deleteUserName');
    
    deleteModal.setAttribute('data-user-id', userId);
    deleteModal.setAttribute('data-user-type', userType);
    
    deleteUserName.textContent = userName || 'this user';
    
    deleteModal.classList.add('active');
}


// Function to handle the delete action
function deleteAccount() {
    const deleteModal = document.getElementById('deleteModal');
    
    // Get the stored data
    const userId = deleteModal.getAttribute('data-user-id');
    const userType = deleteModal.getAttribute('data-user-type');

    // Send the delete request
    fetch('functions/delete_account.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${encodeURIComponent(userId)}&user_type=${encodeURIComponent(userType)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            closeModal('deleteModal');
            setTimeout(() => {
                    location.reload();
                }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete account. Please try again.');
    });
}


// Open the Ban Modal
function openBanModal(userId, userType, currentStatus) {
    const banModal = document.getElementById('banModal');

    const banMessage = banModal.querySelector('p');
    const banButton = banModal.querySelector('button:first-of-type'); // First button is the "Ban/Unban" button
    const isBanned = currentStatus === 'banned';

    banMessage.textContent = isBanned
        ? 'Are you sure that you want to unban this user?'
        : 'Are you sure that you want to ban this user?';

    banButton.textContent = isBanned ? 'Unban' : 'Ban'; // Update button text

    banModal.dataset.userId = userId;
    banModal.dataset.userType = userType;
    banModal.dataset.action = isBanned ? 'unban' : 'ban';

    banModal.classList.add('active');
}



function banAccount() {
    const banModal = document.getElementById('banModal');
    const userId = banModal.dataset.userId;
    const userType = banModal.dataset.userType;
    const action = banModal.dataset.action;

    // Send the appropriate action to the server
    fetch('functions/ban_account.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${userId}&user_type=${userType}&action=${action}`,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === 'success') {
                alert(data.message);
                closeModal('banModal');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        })
        .catch((error) => console.error('Error:', error));
}


// Open the suspend modal with proper configurations
function openSuspendModal(lastName, userId, userType, currentStatus) {
    const suspendUserName = document.getElementById('suspendUserName');
    const suspendDuration = document.getElementById('suspendDuration');
    const durationLabel = document.getElementById('durationLabel');
    const modalTitle = document.getElementById('modalTitle'); // Assuming a modal title exists

    if (!suspendUserName || !modalTitle) {
        console.error("Required modal elements not found in DOM.");
        return;
    }

    // Update modal based on status
    if (currentStatus === 'suspended') {
        suspendUserName.dataset.action = 'unsuspend';
        suspendDuration.style.display = 'none';
        durationLabel.style.display = 'none'; // Hide duration input for unsuspend
        modalTitle.textContent = `Unsuspend ${lastName}`; // Change text to Unsuspend
    } else {
        suspendUserName.dataset.action = 'suspend';
        suspendDuration.style.display = 'block';
        durationLabel.style.display = 'block'; // Show duration input for suspension
        modalTitle.textContent = `Suspend ${lastName}`; // Change text to Suspend
    }

    // Update dataset attributes
    suspendUserName.dataset.userId = userId;
    suspendUserName.dataset.userType = userType;

    // Show the modal
    document.getElementById('suspendModal').classList.add('active');
}



// Perform suspension/unsuspension
function suspendAccount() {
    const suspendUserName = document.getElementById('suspendUserName');
    if (!suspendUserName) {
        console.error("Element 'suspendUserName' not found.");
        return;
    }

    const userId = suspendUserName.dataset.userId;
    const userType = suspendUserName.dataset.userType;
    const action = suspendUserName.dataset.action; // 'suspend' or 'unsuspend'
    const suspendUntil = document.getElementById('suspendDuration').value;

    if (action === 'suspend' && !suspendUntil) {
        alert('Please select a suspension duration.');
        return;
    }

    fetch('functions/suspend_account.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${encodeURIComponent(userId)}&user_type=${encodeURIComponent(userType)}&action=${encodeURIComponent(action)}&suspend_until=${encodeURIComponent(suspendUntil || '')}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                closeModal('suspendModal');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        })
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

window.onclick = function (event) {
    const modals = [document.getElementById('suspendModal'), document.getElementById('banModal'), document.getElementById('deleteModal')];
    modals.forEach(modal => {
        if (modal && event.target === modal) {
            closeModal(modal.id);
        }
    });
};

</script>
</body>
</html>
