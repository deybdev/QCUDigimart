<?php
session_start();
include '../config/config.php';

if (!isset($_GET['seller_id'])) {
    echo "Seller not found.";
    exit;
}

$seller_id = intval($_GET['seller_id']);

// Fetch seller details and products
$query = $conn->prepare("
    SELECT s.*, p.id AS product_id, p.name AS product_name, p.description AS product_description, 
           p.price, p.is_available, p.category_id, p.images, p.date_created AS product_date_created
    FROM seller s
    LEFT JOIN product p ON s.id = p.s_id
    WHERE s.id = ?");
$query->bind_param("i", $seller_id);
$query->execute();
$result = $query->get_result();

$seller = null;
$products = [];

while ($row = $result->fetch_assoc()) {
    if ($seller === null) {
        $seller = [
            'id' => $row['id'],
            'store_name' => $row['store_name'],
            'store_profile' => $row['store_profile'],
            'store_banner' => $row['store_banner'],
            'description' => $row['description'], // Seller's description
        ];
    }

    if ($row['product_id'] !== null) {
        $products[] = $row;
    }
}

if (!$seller) {
    echo "Seller not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($seller['store_name']); ?></title>
</head>
<body>
    <?php include "../main/header.php"; ?>

    <div class="store-banner" style="background: url('<?php echo htmlspecialchars($seller['store_banner'] ?? 'default-banner.jpg'); ?>') center/cover no-repeat;">
        <div class="store-info">
            <div class="store-logo">
                <img src="<?php echo htmlspecialchars($seller['store_profile'] ?? 'default-logo.jpg'); ?>" alt="Store Logo">
            </div>
            <div class="store-details">
                <h4 class="store-name"><?php echo htmlspecialchars($seller['store_name']); ?></h4>
            </div>
        </div>
    </div>

    <div class="store-desc-container">
        <h2>STORE DESCRIPTION</h2>
        <div class="store-description">
            <p><?php echo nl2br(htmlspecialchars($seller['description'])); // Seller description ?></p>
        </div>
    </div>

    <div class="seller-product-page">
        <h2>OUR PRODUCTS</h2>
        <?php if (empty($products)) { ?>
            <p>No products found.</p>
        <?php } else { ?>
            <div class="browse-products">
                <?php foreach ($products as $product) {
                    $images = json_decode($product['images'], true);
                    $image1 = ($images && !empty($images)) ? $images[0] : 'default.jpg'; ?>
                    <div class="browse-product">
                        <div class="product-img" onclick="showModal(
                            <?php echo $product['product_id']; ?>,
                            '<?php echo addslashes($product['product_name']); ?>',
                            '<?php echo addslashes($product['product_description']); ?>',
                            '<?php echo htmlspecialchars(json_encode($images)); ?>',
                            '₱ <?php echo $product['price']; ?>',
                            <?php echo $product['is_available'] ? 'true' : 'false'; ?>)">
                            <img src="<?php echo htmlspecialchars($image1); ?>" alt="Product Image">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

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

    <?php include "../main/footer.php"; ?>

    
<script>
let images = [];
let currentIndex = 0;

function showModal(id, title, description, imageSrcArray, price, availability, isSaved, sellerId) {
    images = JSON.parse(imageSrcArray);
    currentIndex = 0;

    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalDescription').innerText = description;
    document.getElementById('modalImage').src = images[currentIndex];
    document.getElementById('modalPrice').innerText = price;

    const availabilityElement = document.getElementById('modalQuantity');
    availabilityElement.innerText = availability ? "Available" : "Not Available";
    availabilityElement.style.color = availability ? "green" : "red";
    
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
