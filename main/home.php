<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/scrollreveal"></script>

    <title>QCU | Digimart</title>
</head>
<body>
    <section class="home" id="home">
        <div class="home-content">
            <img src="../assets/qcu-logo.png" alt="QCU LOGO">
            <h1>QCU DIGIMART</h1>
            <p>Featured</p>
            <a href="#org-market"><i class="fa-solid fa-arrow-down-long" ></i></a>
        </div>
    </section>
    <!-- ORGS -->
    <section class="org-market" id="org-market" >
        <div class="content">
            <h2>Org's Market</h2>
            <p>Share information on a previous project here to attract new clients. Provide a brief summary to help visitors understand the context and background of the work.</p>
            <a href="#" class="cta-btn">→</a>
        </div>
        <div class="org-images market-images">
            <!-- ORGS IMAGES SECTION-->
            <img src="../assets/t1.jpg" alt="T-Shirt1" class="img1">
            <img src="../assets/t2.jpg" alt="T-Shirt1" class="img2">
        </div>
    </section>
    <!-- ENTREP -->
    <section class="entrep-market">
        <div class="content">
            <h2>Entrep's Enterprise</h2>
            <p>Share information on a previous project here to attract new clients. Provide a brief summary to help visitors understand the context and background of the work.</p>
            <a href="#" class="cta-btn">→</a>
        </div>
        <div class="entrep-images market-images">
            <!-- ENTREP IMAGES SECTION -->
            <img src="../assets/ent1.jpg" alt="T-Shirt1" class="img1">
            <img src="../assets/ent2.jpg" alt="T-Shirt2" class="img2">
        </div>
    </section>
    <!-- CAFETERIA -->
    <section class="cafeteria-market">
        <div class="content">
            <h2>Cafeteria</h2>
            <p>Share information on a previous project here to attract new clients. Provide a brief summary to help visitors understand the context and background of the work.</p>
            <a href="#" class="cta-btn">→</a>
        </div>
        <div class="cafeteria-images market-images">
            <!-- CAFETERIA IMAGES SECTION-->
            <img src="../assets/caf1.jpg" alt="T-Shirt1" class="img1">
            <img src="../assets/caf2.jpg" alt="T-Shirt2" class="img2">
        </div>
    </section>
    <!-- CO-OP -->
    <section class="coop-market">
        <div class="content">
            <h2>CO-OP</h2>
            <p>Share information on a previous project here to attract new clients. Provide a brief summary to help visitors understand the context and background of the work.</p>
            <a href="#" class="cta-btn">→</a>
        </div>
        <div class="coop-images market-images">
            <!-- COOP IMAGES SECTION-->
            <img src="../assets/coop1.jpg" alt="T-Shirt1" class="img1">
            <img src="../assets/coop2.jpg" alt="T-Shirt2" class="img2">
        </div>
    </section>
    <!-- FREE LANCE -->
    <section class="freelance-market">
        <div class="content">
            <h2>Freelance</h2>
            <p>Share information on a previous project here to attract new clients. Provide a brief summary to help visitors understand the context and background of the work.</p>
            <a href="#" class="cta-btn">→</a>
        </div>
        <div class="freelance-images market-images">
            <!-- FREELANCE IMAGES SECTION-->
            <img src="../assets/fl1.jpg" alt="T-Shirt1" class="img1">
            <img src="../assets/fl2.jpg" alt="T-Shirt2" class="img2">
        </div>
    </section>
    </div>

    <?php include'footer.php' ?>

    <script>
        // 
        let subMenu = document.getElementById("sub-menu");
            let arrowIcon = document.getElementById("arrow-icon");

            function toggleMenu(){
                subMenu.classList.toggle("open-menu");
                arrowIcon.classList.toggle("arrow-up");
            }

        // 
        function revealFunction() {
                
            window.sr = ScrollReveal({
                duration: 1150,
                distance: '100px',
                easing: 'ease-out',
                reset: true 
            });

            
            sr.reveal('.content', { origin: 'left' });
            sr.reveal('.img1', { origin: 'top' });
            sr.reveal('.img2', { origin: 'bottom' });
        }

        window.addEventListener('load', () => {
            revealFunction();
        });

        //
        function toggleBar() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
        }
    </script>
    
    

</body>
</html>