<?php
include "../config/config.php";
session_start();

// Validate user session and type
if (isset($_SESSION['customer_id'])) {
    $current_user_id = $_SESSION['customer_id'];
    $user_type = 'customer';
} elseif (isset($_SESSION['seller_id'])) {
    $current_user_id = $_SESSION['seller_id'];
    $user_type = 'seller';
} else {
    die("Please log in to access this page.");
}

// Validate receiver_id from GET request
$receiver_id = filter_input(INPUT_GET, 'receiver_id', FILTER_VALIDATE_INT);
if ($receiver_id) {
    $check_receiver_query = ($user_type === 'customer') 
        ? "SELECT id FROM seller WHERE id = ?" 
        : "SELECT id FROM customer WHERE id = ?";
    
    $check_receiver_stmt = $conn->prepare($check_receiver_query);
    $check_receiver_stmt->bind_param("i", $receiver_id);
    $check_receiver_stmt->execute();
    $check_receiver_result = $check_receiver_stmt->get_result();

    if ($check_receiver_result->num_rows === 0) {
        die("Invalid receiver ID.");
    }
}

// Handle new message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);
    if ($message && $receiver_id) {
        $timestamp = date('Y-m-d H:i:s');
        $query = "INSERT INTO message (sender_id, sender_type, receiver_id, m_content, date_created) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isiss", $current_user_id, $user_type, $receiver_id, $message, $timestamp);
        $stmt->execute();
        exit(); // Prevent further script execution after handling the form
    }
}

// Fetch chat messages
$messages_query = "
    SELECT * FROM message 
    WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
    ORDER BY date_created ASC";
$stmt = $conn->prepare($messages_query);
$stmt->bind_param("iiii", $current_user_id, $receiver_id, $receiver_id, $current_user_id);
$stmt->execute();
$messages_result = $stmt->get_result();


// Fetch recent chats
$recent_chats_query = "
    SELECT 
        IF(sender_id = ?, receiver_id, sender_id) AS chat_partner_id, 
        MAX(date_created) AS last_message_time, 
        SUBSTRING_INDEX(GROUP_CONCAT(m_content ORDER BY date_created DESC), ',', 1) AS last_message 
    FROM message 
    WHERE sender_id = ? OR receiver_id = ? 
    GROUP BY chat_partner_id 
    ORDER BY last_message_time DESC";
$recent_chats_stmt = $conn->prepare($recent_chats_query);
$recent_chats_stmt->bind_param("iii", $current_user_id, $current_user_id, $current_user_id);
$recent_chats_stmt->execute();
$recent_chats_result = $recent_chats_stmt->get_result();

// Fetch receiver's name and profile image
if ($receiver_id) {
    $receiver_query = "
        SELECT store_name AS name, store_profile AS profile_image FROM seller WHERE id = ? 
        UNION 
        SELECT CONCAT(first_name, ' ', last_name) AS name, profile_image FROM customer WHERE id = ?";
    $receiver_stmt = $conn->prepare($receiver_query);
    $receiver_stmt->bind_param("ii", $receiver_id, $receiver_id);
    $receiver_stmt->execute();
    $receiver_result = $receiver_stmt->get_result()->fetch_assoc();

    $receiver_name = $receiver_result['name'] ?? 'Unknown';
    $profile_image = $receiver_result['profile_image'] ?? '../assets/default-profile.png';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <?php include "../main/header.php"; ?>

    <div id="chat-container">
        <!-- Recent Chats Section -->
        <div id="recent-chats">
    <div id="recent-chats-header">Recent Chats</div>
        <div id="recent-chats-list">
        <?php while ($recent_chat = $recent_chats_result->fetch_assoc()):
            $chat_partner_id = $recent_chat['chat_partner_id'];
            $last_message = htmlspecialchars($recent_chat['last_message']);
            $formatted_time = date("M d, h:i A", strtotime($recent_chat['last_message_time']));

            // Fetch chat partner details
            $partner_query = "
                SELECT store_name AS name, store_profile AS profile_image FROM seller WHERE id = ? 
                UNION 
                SELECT CONCAT(first_name, ' ', last_name) AS name, profile_image FROM customer WHERE id = ?";
            $partner_stmt = $conn->prepare($partner_query);
            $partner_stmt->bind_param("ii", $chat_partner_id, $chat_partner_id);
            $partner_stmt->execute();
            $partner_result = $partner_stmt->get_result()->fetch_assoc();

            $partner_name = $partner_result['name'] ?? 'Unknown';
            $partner_image = $partner_result['profile_image'] ?? '../assets/default-profile.png';
        ?>
            <a href="?receiver_id=<?= $chat_partner_id ?>" class="recent-chat-item">
                <img src="<?= $partner_image ?>" alt="User">
                <div class="chat-text">
                    <div class="chat-name"><?= htmlspecialchars($partner_name) ?></div>
                    <div class="last-message"><?= $last_message ?></div>
                    <div class="last-message-time"><?= $formatted_time ?></div>
                </div>
            </a>
        <?php endwhile; ?>
    </div>
</div>
        <!-- Message Section -->
        <div id="message-section">
        <div id="message-header">
            <?php if (isset($receiver_id) && !empty($receiver_name)): ?>
                Chat with <?= htmlspecialchars($receiver_name) ?>
            <?php else: ?>
                Select a chat to start messaging
            <?php endif; ?>
        </div>
            <?php if (isset($receiver_id) && isset($receiver_name)): ?>
    <!-- Message Body -->
        <div id="message-body">
            <?php while ($message = $messages_result->fetch_assoc()): 
                $message_type = ($message['sender_id'] == $current_user_id) ? 'sent' : 'received';
                $formatted_time = date("h:i A", strtotime($message['date_created']));
            ?>
                <div class="message-container <?= $message_type ?>">
                    <img src="<?= $message_type === 'sent' ? '../assets/sent-default.jpg' : $profile_image ?>" alt="User Profile" class="profile-picture">
                    <div class="message <?= $message_type ?>">
                        <?= htmlspecialchars($message['m_content']) ?>
                        <div class="timestamp"><?= $formatted_time ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Message Footer -->
        <div id="message-footer">
            <form method="POST" id="message-form">
                <input type="text" name="message" id="message-input" placeholder="Type your message here..." required>
                <button id="send-button" type="submit"><i class="fa fa-paper-plane"></i></button>
            </form>
        </div>
    <?php else: ?>
        <!-- Hidden sections if no receiver ID or name -->
        <div id="message-body" style="display: none;"></div>
        <div id="message-footer"></div>
    <?php endif; ?>

        </div>
    </div>

    <?php include "../main/footer.php"; ?>

    <script>
        let isAtBottom = true;
        const messageBody = document.getElementById('message-body');

        // Scroll to bottom function
        function scrollToBottom() {
            messageBody.scrollTop = messageBody.scrollHeight;
        }

        // Check scroll position
        messageBody.addEventListener('scroll', () => {
            isAtBottom = messageBody.scrollHeight - messageBody.scrollTop === messageBody.clientHeight;
        });

        // Fetch new messages
        function fetchNewMessages() {
            fetch(`fetch_messages.php?receiver_id=<?= $receiver_id ?>`)
                .then(response => response.json())
                .then(messages => {
                    messageBody.innerHTML = '';
                    messages.forEach(msg => {
                        const msgType = (msg.sender_id == <?= $current_user_id ?>) ? 'sent' : 'received';
                        const formattedTime = new Date(msg.date_created).toLocaleTimeString();
                        const msgDiv = `
                            <div class="message-container ${msgType}">
                                <img src="${msgType === 'sent' ? '../assets/sent-default.jpg' : '<?= $profile_image ?>'}" alt="Profile" class="profile-picture">
                                <div class="message ${msgType}">
                                    ${msg.m_content}
                                    <div class="timestamp">${formattedTime}</div>
                                </div>
                            </div>`;
                        messageBody.innerHTML += msgDiv;
                    });
                    if (isAtBottom) scrollToBottom();
                })
                .catch(err => console.error('Error:', err));
        }

        // Form submission
        document.getElementById('message-form').addEventListener('submit', e => {
            e.preventDefault();
            fetch('', {
                method: 'POST',
                body: new FormData(e.target)
            }).then(() => {
                fetchNewMessages();
                document.getElementById('message-input').value = '';
            }).catch(err => console.error('Error:', err));
        });

        setInterval(fetchNewMessages, 3000);

        scrollToBottom();

        // Fetch new recent chats
        function fetchNewRecentChats() {
    fetch('fetch_recent_chats.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(chats => {
            console.log('Received chats:', chats);
            const recentChatsContainer = document.getElementById('recent-chats-list');
            recentChatsContainer.innerHTML = '';

            if (!Array.isArray(chats)) {
                console.error('Expected an array of chats, got:', chats);
                return;
            }

            chats.forEach(chat => {
                const chatPartnerId = chat.chat_partner_id;
      const lastMessage = chat.last_message;
      const formattedTime = new Date(chat.last_message_time).toLocaleString();
      const chatDiv = `
        <a href="?receiver_id=${chatPartnerId}" class="recent-chat-item">
          <img src="${chat.profile_image || '../assets/default-profile.png'}" alt="User">
          <div class="chat-text">
            <div class="chat-name">${chat.name}</div>
            <div class="last-message">${lastMessage}</div>
            <div class="last-message-time">${formattedTime}</div>
          </div>
        </a>`;
      recentChatsContainer.innerHTML += chatDiv;
            });
        })
        .catch(err => {
            console.error('Error fetching recent chats:', err);
        });
}
// Call the function periodically
setInterval(fetchNewRecentChats, 3000); // Every 3 seconds

    </script>
</body>
</html>
