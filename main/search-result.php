<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include '../main/header.php'; ?>

    <div class="search-results-container">
        <div class="search-result-header">
            <h2>Search Result: <span><?php echo htmlspecialchars($_GET['query']); ?></span></h2>
        </div>

        <div class="browse-products">
            <?php
            include '../config/config.php';

            // Retrieve and sanitize the search query
            $query = isset($_GET['query']) ? $_GET['query'] : '';
            $query = mysqli_real_escape_string($conn, $query);

            try {
                // Fetch products matching the search query
                $stmt = $conn->prepare("
                    SELECT p.*, s.store_name,
                    EXISTS (
                        SELECT 1 FROM saved_products sp 
                        WHERE sp.product_id = p.id AND sp.user_id = ?
                    ) AS is_saved
                    FROM product p
                    JOIN seller s ON p.s_id = s.id
                    WHERE p.name LIKE ? OR p.description LIKE ?
                ");

                $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
                $searchTerm = '%' . $query . '%';
                $stmt->bind_param('iss', $customer_id, $searchTerm, $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();

                // Display the products
                if ($result->num_rows > 0) {
                    while ($product = $result->fetch_assoc()) {
                        $images = json_decode($product['images'], true);
                        $firstImage = ($images && !empty($images)) ? $images[0] : 'default.jpg';

                        echo '<div class="browse-product">';
                        echo '<div class="product-img" onclick="showModal(' . 
                            $product['id'] . ', \'' . addslashes($product['name']) . '\', \'' . 
                            addslashes($product['description']) . '\', \'' . 
                            htmlspecialchars(json_encode($images)) . '\', \'' . 
                            addslashes('₱ ' . $product['price']) . '\', \'' . 
                            addslashes($product['quantity'] . ' pcs available') . '\', \'' . 
                            addslashes($product['store_name']) . '\', ' . 
                            ($product['is_saved'] ? 'true' : 'false') . ', ' . 
                            $product['s_id'] . ')">';
                        echo '<img src="' . htmlspecialchars($firstImage) . '" alt="Product Image">';
                        echo '</div>';
                        echo '<div class="product-info">';
                        echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No products found for your search.</p>";
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="hideModal()">&times;</span>
            <div class="modal-image">
                <i class="fa-solid fa-chevron-left" onclick="prevImage()"></i>
                <img id="modalImage" src="" alt="Product Image">
                <i class="fa-solid fa-chevron-right" onclick="nextImage()"></i>
            </div>
            <div class="modal-right">
                <div class="modal-info">
                    <h3 id="modalTitle"></h3>
                    <p id="modalPrice"></p>
                    <p id="modalDescription"></p>
                    <p id="modalQuantity"></p>
                    <a id="modalStorename"></a>
                </div>
                <div id="reportModal" class="modal">
                    <div class="report-modal-content">
                        <span class="close-report" onclick="closeReportModal()">&times;</span>
                        <a onclick="reportSeller()">Report the Seller</a>
                        <a onclick="reportProduct()">Report the Product</a>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button id="saveButton" class="save-button" onclick="saveProduct(this)">
                        <i id="modalSaveIcon" class="fa-regular fa-heart"></i>
                    </button>
                    <button id="modalMessageButton">
                        <i class="fa-regular fa-message"></i>
                    </button>
                    <button id="modalReportButton" onclick="showReportModal()">
                        <i class="fa-solid fa-circle-info"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include '../main/footer.php'; ?>

<script>
let images = [];
let currentIndex = 0;

function showModal(id, title, description, imageSrcArray, price, quantity, storeName, isSaved, sellerId) {
    images = JSON.parse(imageSrcArray);
    currentIndex = 0;

    // Update modal content dynamically
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalDescription').innerText = description;
    document.getElementById('modalImage').src = images[currentIndex];
    document.getElementById('modalPrice').innerText = price;
    document.getElementById('modalQuantity').innerText = quantity;

    // Update store name link
    const storeLink = document.getElementById('modalStorename');
    storeLink.innerText = "By: " + storeName;
    storeLink.href = `sellers_page.php?seller_id=${sellerId}`;

    // Update save button state
    const saveButton = document.getElementById('saveButton');
    const modalSaveIcon = document.getElementById('modalSaveIcon');
    saveButton.setAttribute('data-product-id', id);
    modalSaveIcon.className = isSaved ? 'fa-solid fa-heart' : 'fa-regular fa-heart';

    // Update message button with the seller ID
    const messageButton = document.getElementById('modalMessageButton');
    messageButton.setAttribute('onclick', `contactSeller(${sellerId})`);

    // Reset modal state and show it
    const modal = document.getElementById('productModal');
    const modalContent = modal.querySelector('.modal-content');
    
    // Remove the transition before showing the modal to reset the state
    modalContent.classList.remove('show'); 
    
    setTimeout(() => {
        modal.style.display = 'flex'; // Make the overlay visible again
        setTimeout(() => {
            modalContent.classList.add('show'); // Reapply transition class after the modal is visible
        }, 10); // Slight delay for smooth transition
    }, 10); // Resetting the display after a tiny delay for proper transition
}

function hideModal() {
    const modal = document.getElementById('productModal');
    const modalContent = modal.querySelector('.modal-content');
    modalContent.classList.remove('show'); // Remove transition class
    
    modal.style.display = 'none';
}


function saveProduct(button) {
    const productId = button.getAttribute('data-product-id');
    const formData = new FormData();
    formData.append('product_id', productId);

    fetch('functions/save_product.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const icon = button.querySelector('i');
            icon.className = data.saved ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
            showSuccessModal(data.message);
        } else {
            showInfoModal(data.message || "An error occurred.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An unexpected error occurred.");
    });
}

function hideModal() {
    document.getElementById('productModal').style.display = 'none';
}

function nextImage() {
    currentIndex = (currentIndex + 1) % images.length;
    document.getElementById('modalImage').src = images[currentIndex];
}

function prevImage() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    document.getElementById('modalImage').src = images[currentIndex];
}

function contactSeller(sellerId) {

    <?php if (!isset($_SESSION['customer_id'])) { ?>
        showInfoModal('Please login first to message the seller.');
        return;
    <?php } ?>

    window.location.href = `../message/chat.php?receiver_id=${sellerId}`;
}

function showReportModal() {
    document.getElementById("reportModal").style.display = "block";
}

</script>
</body>
</html>
