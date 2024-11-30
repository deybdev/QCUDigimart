<?php
include '../config/config.php';
session_start();

// Fetch pending products from the database
$sql = "SELECT pp.*, s.store_name, s.first_name, s.last_name, c.name as category_name
        FROM pending_products pp
        JOIN seller s ON pp.s_id = s.id
        JOIN category c ON pp.category_id = c.id
        ORDER BY pp.date_created DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Products</title>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="wrapper">
    <div class="product-wrapper">
        <div class="header">
            <h1>Pending Products</h1>
            <div class="filter-product">
                <p>Sort by:</p>
                <div class="sort-product">
                    <select name="sort_type" id="sort_type">
                        <option value="all" selected>All</option>
                        <option value="date">Date</option>
                        <option value="name">Name</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="product-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Decode the images JSON array and get the first image
                    $images = json_decode($row['images'], true);
                    $firstImage = $images && isset($images[0]) ? $images[0] : 'default.jpg'; // Use a default image if no images found
                ?>
                    <div class="product-item" 
                        data-id="<?php echo $row['id']; ?>"
                        data-name="<?php echo htmlspecialchars($row['name']); ?>"
                        data-price="<?php echo htmlspecialchars($row['price']); ?>"
                        data-description="<?php echo htmlspecialchars($row['description']); ?>"
                        data-category="<?php echo htmlspecialchars($row['category_name']); ?>"
                        data-uploaded="<?php echo date("F j, Y - g:i A", strtotime($row['date_created'])); ?>"
                        data-store="<?php echo htmlspecialchars($row['store_name']); ?>"
                        data-seller="<?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>">
                
                        <!-- Display the first image and open modal with the first image on click -->
                        <div class="product-image" onclick="openImageModal('<?php echo htmlspecialchars($firstImage); ?>')">
                            <img src="../assets/<?php echo htmlspecialchars($firstImage); ?>" alt="Product Image">
                        </div>
                        
                        <div class="product-details">
                            <p class="product-name"><?php echo htmlspecialchars($row['name']); ?></p>
                            <p class="product-price">&#8369;<?php echo htmlspecialchars($row['price']); ?></p>
                            <p class="product-description"><i><?php echo htmlspecialchars($row['description']); ?></i></p>
                            <p class="product-meta">Uploaded: <?php echo date("F j, Y", strtotime($row['date_created'])); ?><br>Posted by: <?php echo htmlspecialchars($row['store_name']); ?></p>
                        </div>
                        
                        <div class="action-icons">
                            <button class="approve" onclick="openApproveModal(<?php echo $row['id']; ?>)">
                                <i class="fa-solid fa-check"></i>
                            </button>
                            <button class="reject" onclick="openRejectModal(<?php echo $row['id']; ?>)">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <button class="info" onclick="openModal('infoProductModal', this)">
                                <i class="fa-regular fa-circle-question"></i>
                            </button>
                        </div>
                    </div>
                <?php
                }
            } else {
                echo "<p>No pending products found.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="modal">
    <div class="modal-content">
        <form id="approveForm" method="POST" action="functions/approve_product.php">
            <p>Are you sure that you want to <b>Approve</b> this product?</p>
            <input type="hidden" name="id" id="approveProductId"> <!-- Hidden input for product ID -->
            <button type="submit">Yes</button>
            <button type="button" onclick="closeModal('approveModal')">Cancel</button>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <form id="rejectForm" method="POST" action="functions/reject_product.php">
            <p>Are you sure that you want to <b>Reject</b> this product?</p>
            <input type="hidden" name="id" id="rejectProductId"> <!-- Hidden input for product ID -->
            <input type="hidden" name="action" value="reject"> <!-- Hidden input for action -->
            <button type="button" id="confirmReject" onclick="rejectProduct()">Yes</button>
            <button type="button" onclick="closeModal('rejectModal')">Cancel</button>
        </form>
    </div>
</div>


<!-- Info Modal -->
<div id="infoProductModal" class="modal">
    <div class="product-content">
        <h2 id="infoProductName">Product Information</h2>
        <div class="product-detail"><span class="label">Product Name:</span><span class="value" id="infoName"></span></div>
        <div class="product-detail"><span class="label">Price:</span><span class="value" id="infoPrice"></span></div>
        <div class="product-detail"><span class="label">Description:</span><span class="value" id="infoDescription"></span></div>
        <div class="product-detail"><span class="label">Category:</span><span class="value" id="infoCategory"></span></div>
        <div class="product-detail"><span class="label">Uploaded:</span><span class="value" id="infoUploaded"></span></div>
        <div class="product-detail"><span class="label">Store Name:</span><span class="value" id="infoStore"></span></div>
        <div class="product-detail"><span class="label">Seller Name:</span><span class="value" id="infoSeller"></span></div>
        <button onclick="closeModal('infoProductModal')">Close</button>
    </div>
</div>

<script>
// Open the approve modal and set the product ID
function openApproveModal(productId) {
    document.getElementById('approveProductId').value = productId;
    openModal('approveModal');
}

// Open the reject modal and set the product ID
function openRejectModal(productId) {
    document.getElementById('rejectProductId').value = productId;
    openModal('rejectModal');
}

// Open any modal
function openModal(modalId, button = null) {
    const modal = document.getElementById(modalId);
    modal.classList.add('active');

    // Populate the info modal if the modal ID is 'infoModal' and a button is passed
    if (modalId === 'infoProductModal' && button) {
        const productItem = button.closest('.product-item');

        // Populate infoModal fields with values from data attributes
        document.getElementById('infoName').textContent = productItem.getAttribute('data-name');
        document.getElementById('infoPrice').textContent = `₱${productItem.getAttribute('data-price')}`;
        document.getElementById('infoDescription').textContent = productItem.getAttribute('data-description');
        document.getElementById('infoCategory').textContent = productItem.getAttribute('data-category');
        document.getElementById('infoUploaded').textContent = productItem.getAttribute('data-uploaded');
        document.getElementById('infoStore').textContent = productItem.getAttribute('data-store');
        document.getElementById('infoSeller').textContent = productItem.getAttribute('data-seller');
    }
}

// Close any modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Reject Product
function rejectProduct() {
    const productId = document.getElementById('rejectProductId').value;
    
    // Create a new form dynamically and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'functions/reject_product.php';

    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'id';
    inputId.value = productId;

    const inputAction = document.createElement('input');
    inputAction.type = 'hidden';
    inputAction.name = 'action';
    inputAction.value = 'reject';

    form.appendChild(inputId);
    form.appendChild(inputAction);

    document.body.appendChild(form);
    form.submit();
}

</script>

</body>
</html>
