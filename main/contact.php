<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
</head>
    <?php include"../main/header.php" ;?>
    <div class="contact-container">
        <div class="left-contact">
            <h2>Contact Us</h2>
            <p>Got questions about digital marketing? We're here to help! Whether you're looking to boost your online presence, improve your SEO, or launch a successful campaign, feel free to reach out. Let's connect and take your digital marketing to the next level!</p>
        </div>
        <div class="right-contact">
            <div class="form-box">
                <form action="">
                    <div class="form">
                        <div class="form-element half-width">
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" name="first-name" required>
                        </div>
                        <div class="form-element half-width">
                            <label for="last-name">Last Name</label>
                            <input type="text" id="first-name" name="first-name" required>
                        </div>
                        <div class="form-element full-width">
                            <label for="last-name">Email Address</label>
                            <input type="text" id="first-name" name="first-name" required>
                        </div>
                        <div class="form-element full-width">
                            <label for="last-name">What can we help you with?</label>
                            <textarea name="contact-message" id="contact-message"></textarea>
                        </div>
                        <div class="form-element">
                            <button class="btn" name="register">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include"../main/footer.php" ;?>
<body>
    
</body>
</html>