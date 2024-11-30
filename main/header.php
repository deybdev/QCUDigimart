    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    include '../config/config.php';

    $user_type = ''; // Default value

    // Fetch the current logged-in user's ID
    if (isset($_SESSION['seller_id'])) {
        $current_user_id = $_SESSION['seller_id'];
        $user_type = 'seller';
    } elseif (isset($_SESSION['customer_id'])) {
        $current_user_id = $_SESSION['customer_id'];
        $user_type = 'customer';
    }

    $recent_chats_query = "
        SELECT 
            IF(m.sender_id = ?, m.receiver_id, m.sender_id) AS chat_partner_id, 
            MAX(m.date_created) AS last_message_time, 
            SUBSTRING_INDEX(GROUP_CONCAT(m.m_content ORDER BY m.date_created DESC), ',', 1) AS last_message, 
            u.profile_image, 
            IFNULL(s.store_name, u.first_name) AS name, 
            IFNULL(s.store_profile, '') AS store_profile,
            SUM(IF(m.is_read = 0, 1, 0)) AS unread_count  -- Count unread messages
        FROM message m
        LEFT JOIN customer u ON u.id = IF(m.sender_id = ?, m.receiver_id, m.sender_id)
        LEFT JOIN seller s ON s.id = IF(m.sender_id = ?, m.receiver_id, m.sender_id)
        WHERE (m.sender_id = ? OR m.receiver_id = ?)
        GROUP BY chat_partner_id 
        ORDER BY last_message_time";



    // If the user is a seller, bind the seller's ID in the prepared statement
    if ($user_type == 'seller') {
        $recent_chats_stmt = $conn->prepare($recent_chats_query);
        // Corrected: Only pass 5 variables instead of 6
        $recent_chats_stmt->bind_param("iiiii", $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id);
    } else {
        // If the user is a customer, bind the customer’s ID in the prepared statement
        $recent_chats_stmt = $conn->prepare($recent_chats_query);
        // Corrected: Only pass 5 variables instead of 6
        $recent_chats_stmt->bind_param("iiiii", $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id);
    }

    // Execute the prepared statement and get the result
    $recent_chats_stmt->execute();
    $recent_chats_result = $recent_chats_stmt->get_result();

    // Initialize the array to store recent chats
    $recent_chats = [];
    while ($row = $recent_chats_result->fetch_assoc()) {
        $recent_chats[] = $row;
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/cssagain.css">
        <title>Header</title>

        <style>

        </style>

    </head>

    <body>
        <?php include '../main/modal.php'; ?>
        <div class="navbar">
            <div class="hamburger" onclick="toggleBar()">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="logo-container">
                <div class="image-logo">
                    <img src="../assets/digilogo.png" alt="Logo">
                </div>
            </div>

            <div class="nav-menu-links">
                <div class="nav-links">
                    <ul>
                        <li><a href="../main/main-home.php">Home</a></li>
                        <li class="dropdown-header">
                            <a href="#">Browse <i class="fa-solid fa-chevron-down" style="margin-left: 15px;"></i></a>
                            <ul class="dropdown-browse">
                                <li><a href="../main/browse.php?org_type=market">Org's Market</a></li>
                                <li><a href="../main/browse.php?org_type=enterprise">Entrep's Enterprise</a></li>
                                <li><a href="../main/browse.php?org_type=cafeteria">Cafeteria</a></li>
                                <li><a href="../main/browse.php?org_type=coop">CO-OP</a></li>
                                <li><a href="../main/browse.php?org_type=freelance">Freelance</a></li>
                            </ul>
                        </li>
                        <li><a href="../main/about.php">About</a></li>
                        <li><a href="../main/contact.php">Contact</a></li>
                    </ul>
                </div>

                <div class="search-bar">
                    <form action="../main/search-result.php" method="GET">
                        <input type="text" name="query" placeholder="Search Product" required>
                        <button type="submit"><i class="fa-solid fa-magnifying-glass" aria-label="search-icon"></i></button>
                    </form>
                </div>

                <!-- If Seller is logged in -->
                <?php if(isset($_SESSION['seller_first_name']) && isset($_SESSION['seller_last_name'])) : ?>
                <div class="header-nav-icons">
                    <i class="fa-regular fa-message" onclick="toggleMessages()" style="cursor: pointer;"></i>
                                    <!-- Check if there are unread messages -->
                    <?php if (!empty($recent_chats)): ?>
                            <?php foreach ($recent_chats as $chat): ?>
                                <?php if ($chat['unread_count'] > 0): ?>
                                    <span class="new-message-notify"></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <div id="messageDropdown" class="dropdown-message">
                        <h3>Recent Chats</h3>
                        <div class="recent-chats">
        <?php if (!empty($recent_chats)): ?>
            <?php foreach ($recent_chats as $chat): ?>
            <a class="chat-item" href="../message/chat.php?receiver_id=<?php echo $chat['chat_partner_id']; ?>">
                <!-- Display store profile if the chat partner is a seller, else display customer profile image -->
                <img src="<?php echo !empty($chat['store_profile']) ? $chat['store_profile'] : $chat['profile_image']; ?>" alt="Profile Image" class="chat-profile-img">
                
                <div class="chat-text">
                    <div class="chat-name"><?php echo $chat['name']; ?></div>
                    <div class="last-message"><?php echo $chat['last_message']; ?></div>
                    <div class="last-message-time"><?php echo $chat['last_message_time']; ?></div>
                </div>
            </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-chats-message">No recent chats</p>
        <?php endif; ?>
    </div>


            </div>
                    <div class="nav-icons" onclick="toggleMenu()">
                        <i class="fa-regular fa-user"></i>
                        <p><?php echo htmlspecialchars($_SESSION['seller_first_name']); ?><a id="arrow-icon" class="fa-solid fa-angle-down"></a></p>
                    </div>
                </div>
            <div class="submenu-wrap" id="sub-menu">
                <div class="sub-menu">
                    <div class="user-info">
                        <h2><?php echo htmlspecialchars($_SESSION['seller_first_name']) . ' ' . htmlspecialchars($_SESSION['seller_last_name']); ?></h2>
                        <p><?php echo htmlspecialchars($_SESSION['seller_email']); ?></p>
                    </div>
                    <hr>
                    <a href="../seller/page.php" class="sub-menu-link">
                        <i class="fa-regular fa-pen-to-square"></i>
                        <p>Our Page</p>
                        <span class="fa-solid fa-chevron-right"></span>
                    </a>
                    <a href="../seller/dashboard.php" class="sub-menu-link">
                        <i class="fa-solid fa-basket-shopping"></i>
                        <p>Manage Products</p>
                        <span class="fa-solid fa-chevron-right"></span>
                    </a>
                    <a href="../uploads/logout.php" class="sub-menu-link">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <p>Logout</p>
                        <span class="fa-solid fa-chevron-right"></span>
                    </a>
                </div>
            </div>

            <!-- If Customer is logged in -->
            <?php elseif(isset($_SESSION['customer_first_name']) && isset($_SESSION['customer_last_name'])) : ?>
            <div class="header-nav-icons">
                <i class="fa-regular fa-message" onclick="toggleMessages()" style="cursor: pointer;"></i>
                <!-- Check if there are unread messages -->
                    <?php if (!empty($recent_chats)): ?>
                        <?php foreach ($recent_chats as $chat): ?>
                            <?php if ($chat['unread_count'] > 0): ?>
                                <span class="new-message-notify"></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <div id="messageDropdown" class="dropdown-message">
                    <h3>Recent Chats</h3>
                    <div class="recent-chats">
        <?php if (!empty($recent_chats)): ?>
            <?php foreach ($recent_chats as $chat): ?>
            <a class="chat-item" href="../message/chat.php?receiver_id=<?php echo $chat['chat_partner_id']; ?>">
                <!-- Display store profile if the chat partner is a seller, else display customer profile image -->
                <img src="<?php echo !empty($chat['store_profile']) ? $chat['store_profile'] : $chat['profile_image']; ?>" alt="Profile Image" class="chat-profile-img">
                
                <div class="chat-text">
                    <div class="chat-name"><?php echo $chat['name']; ?></div>
                    <div class="last-message"><?php echo $chat['last_message']; ?></div>
                    <div class="last-message-time"><?php echo $chat['last_message_time']; ?></div>
                </div>
            </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-chats-message">No recent chats</p>
        <?php endif; ?>
    </div>


                </div>
                <div class="nav-icons" onclick="toggleMenu()">
                    <i class="fa-regular fa-user"></i>
                    <p><?php echo htmlspecialchars($_SESSION['customer_first_name']); ?><a id="arrow-icon" class="fa-solid fa-angle-down"></a></p>
                </div>
            </div>
            <div class="submenu-wrap" id="sub-menu">
                <div class="sub-menu">
                    <div class="user-info">
                        <h2><?php echo htmlspecialchars($_SESSION['customer_first_name']) . ' ' . htmlspecialchars($_SESSION['customer_last_name']); ?></h2>
                        <p><?php echo htmlspecialchars($_SESSION['customer_email']); ?></p>
                    </div>
                    <hr>
                    <a href="../customer/update_profile.php" class="sub-menu-link">
                        <i class="fa-regular fa-pen-to-square"></i>
                        <p>Update Profile</p>
                        <span class="fa-solid fa-chevron-right"></span>
                    </a>
                    <a href="../customer/saved_products.php" class="sub-menu-link">
                        <i class="fa-regular fa-heart"></i>
                        <p>Saved Products</p>
                        <span class="fa-solid fa-chevron-right"></span>
                    </a>
                    <a href="../uploads/logout.php" class="sub-menu-link">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <p>Logout</p>
                        <span class="fa-solid fa-chevron-right"></span>
                    </a>
                </div>
            </div>

            <!-- If no one is logged in -->
            <?php else: ?>
            <div class="nav-icons">
                <p><a href="../uploads/login.php">Login</a> | <a href="../uploads/register.php">Register</a></p>
            </div>
            <?php endif; ?>

            </div>
        </div>

        <div id="successModal" class="success-modal">
            <div class="success-modal-content">
                <div class="check-animation">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" class="checkmark">
                        <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                        <path class="checkmark-check" fill="none" d="M14 26l8 8 17-17" />
                    </svg>
                </div>
                <h2>Success</h2>
                <p id="successMessage">Your action was successful!</p>
                <button onclick="closeModal('successModal')">OK</button>
            </div>
        </div>

        <script src="../script.js"></script>

        <script>
            function toggleMessages() {
    const dropdown = document.getElementById("messageDropdown");
    const notification = document.querySelector(".new-message-notify");

    dropdown.classList.toggle("visible");

    // If notification exists, send an AJAX request to mark messages as read
    if (notification) {
        fetch('../message/mark_messages_read.php', {
            method: 'POST',
            // You might want to send additional data like user ID if needed
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the notification dot
                notification.remove();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}


            function toggleBar() {
                const hamburger = document.querySelector('.hamburger');
                const navLinks = document.querySelector('.nav-links');
                
                hamburger.classList.toggle('active'); // Toggle the active class
                navLinks.classList.toggle('active'); // Toggle the nav links visibility
            }

            function toggleMenu() {
                const messageDropdown = document.getElementById("messageDropdown");
                const subMenu = document.getElementById("sub-menu");
                const arrowIcon = document.getElementById("arrow-icon");

                // Close message dropdown if it's open
                if (messageDropdown.classList.contains("visible")) {
                    messageDropdown.classList.remove("visible");
                }

                // Toggle the sub-menu for the user options
                subMenu.classList.toggle("open-menu");
                arrowIcon.classList.toggle("arrow-up");
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                modal.style.display = "none";
            }
        </script>
    </body>
    </html>