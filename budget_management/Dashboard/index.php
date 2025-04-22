<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Broke</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
<body>
    <div class="sideBar">
        <div class="sideBarTitle">
            <h2><span class="fullText"><span class="red">Go</span><span class="green">Broke</span></span></h2>
            <h3><span class="shortText"><span class="red">g</span><span class="green">B</span></span></h3>
            <button class="toggleButton" onclick="toggleSideBar()"><i class="fa-solid fa-bars"></i></button>
        </div>

        <nav>
            <ul>
                <li><a href="../Dashboard/index.php"><i class="fas fa-home"></i> <span>Home</span></a></li>
                <li><a href="../Income/index.php"><i class="fas fa-wallet"></i> <span>Income Tracker</span></a></li>
                <li><a href="../Expenses/index.php"><i class="fas fa-chart-line"></i> <span>Expense Tracker</span></a></li>
                <li><a href="../Reminders/index.php"><i class="fas fa-bell"></i> <span>Reminders</span></a></li>
            </ul>
        </nav>

        <ul class="logout">
            <li><a href="../Login/login.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
        </ul>
    </div>


    <div class="mainContainer">
        <header>
            <h1>Dashboard</h1>
            <p>Welcome back, Craig</p>
        </header>

        <div class="savingsCard">
            <canvas id="goalChart"></canvas>

            <div class="chartText" id="chartText">₹0.00</div>
        </div>

        <div class="analytics">
            <div class="incomeCard">
                <h4>Income</h4>
                <div class="card">
                    <p class="income">₹89,000.70</p>
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>
            <div class="expenseCard">
                <h4>Expenses</h4>
                <div class="card">
                    <p class="expenses">₹42,000.30</p>
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>
            <div class="remindersCard">
                <h4>Reminders</h4>
                <div class="reminders">
                    <ul class="remindersList">
                        <?php
                        session_start();
                        $user_id = $_SESSION['user_id'] ?? 1;

                        include("../Registration/database.php");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $upcomingReminders = $conn->query("SELECT reminder_name, reminder_amount FROM reminders WHERE user_id = $user_id ORDER BY reminder_date ASC LIMIT 5");

                        if ($upcomingReminders->num_rows > 0):
                            while ($row = $upcomingReminders->fetch_assoc()):
                        ?>
                                    <li><?= htmlspecialchars($row['reminder_name']) ?> - ₹<?= number_format($row['reminder_amount']) ?></li>
                        <?php
                            endwhile;
                        else:
                        ?>
                                    <li>No upcoming reminders</li>
                        <?php endif;

                        $conn->close();
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="charts.js"></script>

</body>
</html>