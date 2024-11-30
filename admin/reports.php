<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
</head>
<body>
    <?php include'sidebar.php'; ?>

    <div class="wrapper">
        <h1>Reports</h1>
            <div class="filter-report">
                    <p>Sort by: </p>
                <div class="sort-report-accounts">
                    <select name="user_type" id="user_type">
                        <option value="all" selected>All</option>
                        <option value="flagged">Flagged</option>
                        <option value="ewan">Ewan</option>
                    </select>
                </div>
            </div>
        <div class="reports-container">
            <!-- LEFT SIDE -->
             <div class="reports-table">
                <table id="default-table">
                    <tr>
                        <th>ID</th>
                        <th>Complainant</th>
                        <th>Reported</th>
                        <th>Report Type</th>
                        <th>Submitted At</th>
                        <th>Proof</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>John Doe <span class="user-type"><br>(C)</span></td>
                        <td>Store ABC <span class="user-type"><br>(S)</span></td>
                        <td>Fraud</td>
                        <td>2024-10-20 14:30</td>
                        <td><a href="#">View Proof</a></td>
                        <td><button>Resolve</button><button>Ignore</button></td>

                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jane Smith <span class="user-type"><br>(S)</span></td>
                        <td>Store XYZ <span class="user-type"><br>(C)</span></td>
                        <td>Product Issue</td>
                        <td>2024-10-21 09:15</td>
                        <td><a href="#">View Proof</a></td>
                        <td><button>Resolve</button><button>Ignore</button></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Mike Johnson <span class="user-type"><br>(C)</span></td>
                        <td>Store LMN <span class="user-type"><br>(S)</span></td>
                        <td>Harassment</td>
                        <td>2024-10-22 17:45</td>
                        <td><a href="#">View Proof</a></td>
                        <td><button>Resolve</button><button>Ignore</button></td>
                    </tr>
                </table>
            </div>
            <!-- Right Section -->
            <div class="right-section">
                <div class="flagged-count">
                    <div class="flagged-info">    
                        <p>Flagged Accounts:</p>
                        <h2>43</h2>
                    </div>
                </div>
                <div class="recent-reports-container">
                    <h3>Recent Activity</h3>
                        <div class="recent-box">
                            <div class="recent-image">
                                <img src="../assets/CAHILIG.jpg" alt="haha">
                            </div>
                            <div class="recent-info">
                                <h4>Efren Dave Cahilig</h4>
                                <p>June 19 2024 - 8:00 AM</p>
                                <a href="#">Account Suspended</a>
                            </div>
                        </div>
                        <div class="recent-box">
                            <div class="recent-image">
                                <img src="../assets/AMBINOC.png" alt="haha">
                            </div>
                            <div class="recent-info">
                                <h4>Ahmad Ambinoc</h4>
                                <p>October 18 2024 - 7:56 AM</p>
                                <a href="#">Account Banned</a>
                            </div>
                        </div>
                        <div class="recent-box">
                            <div class="recent-image">
                                <img src="../assets/DE LEON.jpg" alt="haha">
                            </div>
                            <div class="recent-info">
                                <h4>KARL DE LEON</h4>
                                <p>November 10 2024 - 6:56 PM</p>
                                <a href="#">Account Suspended</a>
                            </div>
                        </div>
                        
                </div>

            </div>
    </div>

</body>
</html>