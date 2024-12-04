
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Product</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include '../main/header.php'; ?>
<div class="browse-container">
    <?php
    include '../config/config.php';

    $org_type = isset($_GET['org_type']) ? $_GET['org_type'] : 'enterprise';

    $org_titles = [
        'market' => "Org's Market",
        'enterprise' => "Entrep's Enterprise",
        'cafeteria' => 'Cafeteria',
        'coop' => 'CO-OP',
        'freelance' => 'Freelance'
    ];
    $org_title = isset($org_titles[$org_type]) ? $org_titles[$org_type] : 'Products';

    echo "<h2>$org_title</h2>";

    try {
        // Example: Set session variable when the user logs in
        $_SESSION['user_type'] = 'seller'; // 'customer' or 'seller'
        $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;

        $stmt = $conn->prepare("
            SELECT p.*, s.store_name, s.org_type,
            EXISTS (
                SELECT 1 FROM saved_products sp 
                WHERE sp.product_id = p.id AND sp.user_id = ? 
            ) AS is_saved
        FROM product p
        JOIN seller s ON p.s_id = s.id
        WHERE s.org_type = ?");

        $stmt->bind_param('is', $customer_id, $org_type);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo "No products found.";
        }

        echo '<div class="browse-products">';
        while ($product = $result->fetch_assoc()) {
            $images = json_decode($product['images'], true);
            $image1 = ($images && !empty($images)) ? $images[0] : 'default.jpg';

            echo '<div class="browse-product">';
            echo '<div class="product-img" onclick="showModal(' . 
                $product['id'] . ', \'' . addslashes($product['name']) . '\', \'' . 
                addslashes($product['description']) . '\', \'' . 
                htmlspecialchars(json_encode($images)) . '\', \'' . 
                addslashes('₱ ' . $product['price']) . '\', ' . 
                ($product['is_available'] ? 'true' : 'false') . ', \'' . 
                addslashes($product['store_name']) . '\', ' . 
                ($product['is_saved'] ? 'true' : 'false') . ', ' . 
                $product['s_id'] . ')">'; 
            echo '<img src="' . htmlspecialchars($image1) . '" alt="Product Image">';
            echo '</div>';
            echo '<div class="product-info">';
            echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>
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
                <button id="modalMessageButton" onclick="">
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

function showModal(id, title, description, imageSrcArray, price, availability, storeName, isSaved, sellerId) {
    images = JSON.parse(imageSrcArray);
    currentIndex = 0;

    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalDescription').innerText = description;
    document.getElementById('modalImage').src = images[currentIndex];
    document.getElementById('modalPrice').innerText = price;

    const availabilityElement = document.getElementById('modalQuantity');
    availabilityElement.innerText = availability ? "Available" : "Not Available";
    availabilityElement.style.color = availability ? "green" : "red";

    const storeLink = document.getElementById('modalStorename');
    storeLink.innerText = "By: " + storeName;
    storeLink.href = `sellers_page.php?seller_id=${sellerId}`;

    const saveButton = document.getElementById('saveButton');
    const modalSaveIcon = document.getElementById('modalSaveIcon');
    saveButton.setAttribute('data-product-id', id);
    modalSaveIcon.className = isSaved ? 'fa-solid fa-heart' : 'fa-regular fa-heart';

    const messageButton = document.getElementById('modalMessageButton');
    messageButton.setAttribute('onclick', `contactSeller(${sellerId})`);

    const modal = document.getElementById('productModal');
    const modalContent = modal.querySelector('.modal-content');
    
    modalContent.classList.remove('show'); 
    
    setTimeout(() => {
        modal.style.display = 'flex';
        setTimeout(() => {
            modalContent.classList.add('show');
        }, 10);
    }, 10);
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
        button.disabled = false;
        console.error('Error:', error);
        alert("An unexpected error occurred.");
    });
}

function contactSeller(sellerId) {
    <?php if (!isset($_SESSION['customer_id'])) { ?>
        showInfoModal('Please login first to message the seller.');
        return;
    <?php } ?>

    // Redirect to the chat page with the seller_id
    window.location.href = `../message/chat.php?receiver_id=${sellerId}`;
}

function reportProduct() {
    const productName = document.getElementById('modalTitle').innerText;
    const productImage = document.getElementById('modalImage').src;
    const productId = document.getElementById('saveButton').getAttribute('data-product-id'); // Extract the product ID

    // Redirect to report.php with product details
    window.location.href = `../report/report.php?product_id=${encodeURIComponent(productId)}&product_name=${encodeURIComponent(productName)}&product_image=${encodeURIComponent(productImage)}`;
}


function reportSeller() {
    // Redirect to report.php with seller information
    const storeName = document.getElementById('modalStorename').innerText.replace('By: ', ''); // Extract seller name
    const sellerId = document.getElementById('modalMessageButton').getAttribute('onclick').match(/\d+/)[0]; // Extract seller ID

    window.location.href = `../report/report.php?seller_name=${encodeURIComponent(storeName)}&seller_id=${encodeURIComponent(sellerId)}`;
}

function nextImage() {
    currentIndex = (currentIndex + 1) % images.length;
    document.getElementById('modalImage').src = images[currentIndex];
}

function prevImage() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    document.getElementById('modalImage').src = images[currentIndex];
}

window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target === modal) {
        hideModal();
    }
};

function showReportModal() {
    document.getElementById("reportModal").style.display = "block";
}

function closeReportModal() {
    document.getElementById("reportModal").style.display = "none";
}



</script>

</body>
</html>