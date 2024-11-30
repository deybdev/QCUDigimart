// SIDEBAR JS START

    document.addEventListener("DOMContentLoaded", function() {
        const currentLocation = window.location.href;

        // Apply active class based on the page
        if (currentLocation.includes("dashboard.php")) {
            document.getElementById("dashboard").classList.add("active");
        } else if (currentLocation.includes("accounts.php")) {
            document.getElementById("accounts-li").classList.add("active");
        } else if (currentLocation.includes("reports.php")) {
            document.getElementById("reports").classList.add("active");
        } else if (currentLocation.includes("pending-sellers.php")) {
            document.querySelector(".dropdown-content").style.display = "block";
            document.querySelector(".fa-chevron-down").classList.add("active");
            document.getElementById("pending-sellers").parentElement.classList.add("active");
        } else if (currentLocation.includes("pending-products.php")) {
            document.querySelector(".dropdown-content").style.display = "block";
            document.querySelector(".fa-chevron-down").classList.add("active");
            document.getElementById("pending-products").parentElement.classList.add("active");
        }
    });

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

// SIDEBAR JS END 
