<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/transcss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/scrollreveal"></script>
    <title>Footer</title>
</head>
<body>
    <footer>
    <div class="footer-container">
        <!-- Contact Info -->
        <div class="footer-column">
            <div class="footer-info">
                <p>Mail: qcu.digimart@gmail.com</p>
                <p>Tel: 123-456-7890</p>
                <p>673 Quirino Highway, San Bartolome</p>
                <p>Novaliches, Quezon City</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="footer-column">
            <ul class="footer-links">
                <li><a href="../main/main-home.php">Home</a></li>
                <li class="dropdown">
                    <a href="../main/all_projects.php">All Projects</a><i class="fa-solid fa-chevron-down" onclick="toggleDropdown()"></i>
                    <ul class="dropdown-content">
                        <li><a href="../main/browse.php?org_type=market">Org's Market</a></li>
                        <li><a href="../main/browse.php?org_type=enterprise">Entrep's Enterprise</a></li>
                        <li><a href="../main/browse.php?org_type=cafeteria">Cafeteria</a></li>
                        <li><a href="../main/browse.php?org_type=coop">Coop</a></li>
                        <li><a href="../main/browse.php?org_type=freelance">Freelance</a></li>
                    </ul>
                </li>
                <li><a href="../main/about.php">Info</a></li>
            </ul>
        </div>

        <!-- Social Media & Copyright -->
        <div class="footer-column">
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-telegram"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                
            <p>&copy; 2024 by QCU. Made with <a href="#">GROUP 1</a></p>
            </div>
        </div>
    </div>
    </footer>

    <script>
        function toggleDropdown() {
            var dropdown = document.querySelector(".dropdown-content");
            var arrow = document.querySelector(".fa-chevron-down");

            if (dropdown.style.display === "block") {
                dropdown.style.display = "none";
                arrow.classList.remove("active");
            } else {
                dropdown.style.display = "block";
                arrow.classList.add("active");
            }
        }

        

        
    </script>
</body>
</html>