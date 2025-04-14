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

        <div class="totalSavings">
            <h3>Total Savings</h3>
            <div class="savingsCard">
                <canvas id="goalChart"></canvas>
                <p class="savings-amount">₹5,432,000</p>
            </div>
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
                        <li>Electricity Bill - ₹2,100</li>
                        <li>Internet Subscription - ₹5,000</li>
                        <li>Credit Card Payment - ₹17,000</li>
                        <li>Stationary Supplies - ₹2,199</li>
                        <li>Book Supplies - ₹899</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    
    <script src="script.js"></script>
</body>
</html>