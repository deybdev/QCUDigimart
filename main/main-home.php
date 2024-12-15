<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <?php include "../main/header.php"; ?>
    
    <?php
    if (isset($_SESSION['success_message'])) {
        $infoMessage = htmlspecialchars(addslashes($_SESSION['success_message']));
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    const infoMessage = "' . $infoMessage . '";
                    showSuccessModal(infoMessage); 
                });
              </script>';
        unset($_SESSION['success_message']);
    }
    ?>

    <section id="hero-section">
        <div class="hero-content">
            <h1 style="text-align: start;">Browse, Save, and Connect — Your Marketplace Awaits</h1>
            <p>QCU DIGIMART connects your products with customers where they shop and engage. Our platform offers seamless communication between sellers and buyers in a dynamic digital marketplace.</p>
                <a href="../main/browse.php">
                    <button class="main-home-btn">BROWSE NOW <i class="fa-solid fa-arrow-right"></i></button>
                </a>
        </div>
    </section>

    <?php include "../main/footer.php"; ?>
</body>
</html>