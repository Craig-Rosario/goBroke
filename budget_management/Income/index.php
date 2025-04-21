<?php
session_start();
include("../Registration/database.php");

$error = "";

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// DELETE Income
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM incomes WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);

    if ($stmt->execute()) {
        // Optional: calculate total income if you use it in frontend
        $totalIncome = $conn->query("SELECT SUM(income_amount) AS total FROM incomes WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0;

        echo "<script>const totalIncome = " . json_encode($totalIncome) . ";</script>";
        header("Location: index.php");
        exit();
    } else {
        $error = "Error deleting income: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch income goal (if any) for the user
$stmt = $conn->prepare("SELECT income_goal FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($income_goal);
$stmt->fetch();
$stmt->close();

// INSERT or UPDATE Income and Goal
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $income_name = trim($_POST['income_name'] ?? '');
    $income_amount = floatval($_POST['income_amount'] ?? 0);
    $income_date = $_POST['income_date'] ?? '';
    $income_category = trim($_POST['income_category'] ?? '');

    // Handle income goal update if provided
    if (isset($_POST['goal_amount'])) {
        $new_income_goal = floatval($_POST['goal_amount']);

        if ($new_income_goal >= 0) {
            $stmt = $conn->prepare("UPDATE users SET income_goal = ? WHERE id = ?");
            $stmt->bind_param("di", $new_income_goal, $user_id);

            if ($stmt->execute()) {
                // Optionally update the page or redirect
                header("Location: index.php?success=Income goal updated successfully");
                exit();
            } else {
                $error = "Error updating income goal: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Please provide a valid income goal.";
        }
    }

    // Handle adding or updating incomes
    if ($income_name && $income_amount && $income_date && $income_category) {
        if (!empty($_POST['income_id'])) {
            // UPDATE existing income
            $income_id = (int) $_POST['income_id'];

            $stmt = $conn->prepare("UPDATE incomes SET income_name = ?, income_amount = ?, income_date = ?, income_category = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("sdssii", $income_name, $income_amount, $income_date, $income_category, $income_id, $user_id);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Error updating income: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // INSERT new income
            $stmt = $conn->prepare("INSERT INTO incomes (user_id, income_name, income_amount, income_date, income_category) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isdss", $user_id, $income_name, $income_amount, $income_date, $income_category);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Error adding income: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $error = "Please fill in all the required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Broke</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
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
                <li><a href="../Expenses/index.php"><i class="fas fa-chart-line"></i> <span>Expense Tracker</span></a>
                </li>
                <li><a href="../Reminders/index.php"><i class="fas fa-bell"></i> <span>Reminders</span></a></li>
            </ul>
        </nav>
        <ul class="logout">
            <li><a href="../Login/login.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a>
            </li>
        </ul>
    </div>

    <div class="mainContainer">
        <header>
            <h1>Income Tracker</h1>
        </header>

        <div class="income">
            <div class="totalIncome">
                <h3>Total Income</h3>
                <div class="incomeCard">
                    <div class="chartWrapper">
                        <canvas id="goalChart1"></canvas>
                        <div class="chartText"><p class="income-amount">₹0</p></div>
                    </div>
                </div>
            </div>

            <div class="incomeChart">
                <h3>Income Distribution</h3>
                <div class="incomeCard">
                    <canvas id="incomeCategoryChart"></canvas>
                </div>
            </div>
        </div>
        <button class="addBtn" onclick="openGoalForm()"><i class="fa-solid fa-bullseye"></i> Set Income Goal</button>

        <div class="analytics">
            <h3>All Incomes</h3>
            <button class="addBtn" onclick="openForm()"><i class="fa-solid fa-plus"></i>Add</button>
            <div class="incomeTable">
                <div class="tableCard">
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM incomes WHERE user_id = ?");
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['income_name'] ?></td>
                            <td style="color:#00FF7F"><?= $row['income_amount'] ?></td>
                            <td><?= $row['income_date'] ?></td>
                            <td><?= $row['income_category'] ?></td>
                            <td><a href="javascript:void(0);" class="editBtn"
                                   data-id="<?= $row['id'] ?>"
                                   data-name="<?= $row['income_name'] ?>"
                                   data-amount="<?= $row['income_amount'] ?>"
                                   data-date="<?= $row['income_date'] ?>"
                                   data-category="<?= $row['income_category'] ?>"
                                   onclick="openEditForm(this)"><i style="color:white;" class="fa-solid fa-pen-to-square"></i></a></td>
                            <td><a href="index.php?delete_id=<?= $row['id'] ?>" style="color:#FF4C4C;"><i class="fa-solid fa-trash"></i></a></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="addForm" id="addForm">
        <div class="addFormCard">
            <h1>Add New Income</h1>
            <button class="closeBtn" onclick="closeForm()"><i class="fa-solid fa-xmark"></i></button>
            <?php if (!empty($error)): ?>
            <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>
            <form method="POST" action="index.php">
                <div class="incName">
                    <label for="incName">Income Name</label>
                    <input type="text" id="incName" name="income_name" placeholder="Income" required>
                </div>
                <div class="incAmt">
                    <label for="incAmt">Income Amount</label>
                    <input type="number" id="incAmt" name="income_amount" placeholder="Enter Amount" required>
                </div>
                <div class="incDate">
                    <label for="incDate">Income Date</label>
                    <input type="date" id="incDate" name="income_date" required>
                </div>
                <div class="incCat">
                    <label for="incCat">Income Category</label>
                    <input type="text" id="incCat" name="income_category" placeholder="Enter Category" required>
                </div>
                <button class="submitForm" type="submit">Submit</button>
            </form>
        </div>
    </div>

    <div class="editOverlay" id="editOverlay"></div>
    <div class="editForm" id="editForm">
        <div class="addFormCard">
            <h1>Edit Income</h1>
            <button class="closeBtn" onclick="closeEditForm()"><i class="fa-solid fa-xmark"></i></button>
            <form method="POST" action="index.php">
                <input type="hidden" id="editId" name="income_id">
                <div class="incName">
                    <label for="editIncName">Income Name</label>
                    <input type="text" id="editIncName" name="income_name" required>
                </div>
                <div class="incAmt">
                    <label for="editIncAmt">Income Amount</label>
                    <input type="number" id="editIncAmt" name="income_amount" required>
                </div>
                <div class="incDate">
                    <label for="editIncDate">Income Date</label>
                    <input type="date" id="editIncDate" name="income_date" required>
                </div>
                <div class="incCat">
                    <label for="editIncCat">Income Category</label>
                    <input type="text" id="editIncCat" name="income_category" required>
                </div>
                <button class="submitForm" type="submit">Save</button>
            </form>
        </div>
    </div>

    <div class="goalOverlay" id="goalOverlay"></div>
    <div class="addGoalForm" id="goalForm">
        <div class="addIncomeFormCard">
            <h1>Set Income Goal</h1>
            <button class="closeBtn" onclick="closeGoalForm()"><i class="fa-solid fa-xmark"></i></button>
            <form method="POST" action="index.php" id="goalInputForm">
                <div class="goalAmt">
                    <label for="goalAmt">Goal Amount</label>
                    <input type="number" id="goalAmt" name="goal_amount" required>
                </div>
                <button class="submitForm" type="submit">Set Goal</button>
            </form>
        </div>
    </div>

    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const totalIncome = <?= json_encode($conn->query("SELECT SUM(income_amount) as total FROM incomes WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0); ?>;
        const goalAmount = <?= json_encode($conn->query("SELECT income_goal FROM users WHERE id = $user_id")->fetch_assoc()['income_goal'] ?? 0); ?>;
    </script>
    <script src="charts.js"></script>
</body>

</html>