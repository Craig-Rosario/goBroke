<?php
session_start();
include("../Registration/database.php");

$error = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);

    if ($stmt->execute()) {
        $totalExpense = $conn->query("SELECT SUM(expense_amount) AS total FROM expenses WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0;
        echo "<script>const totalExpense = " . json_encode($totalExpense) . ";</script>";
        header("Location: index.php");
        exit();
    } else {
        $error = "Error deleting expense: " . $stmt->error;
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT expense_limit FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($expense_limit);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $expense_name = trim($_POST['expense_name'] ?? '');
    $expense_amount = floatval($_POST['expense_amount'] ?? 0);
    $expense_date = $_POST['expense_date'] ?? '';
    $expense_category = trim($_POST['expense_category'] ?? '');

    if (isset($_POST['expense_limit'])) {
        $new_expense_limit = floatval($_POST['expense_limit']);

        if ($new_expense_limit >= 0) {
            $stmt = $conn->prepare("UPDATE users SET expense_limit = ? WHERE id = ?");
            $stmt->bind_param("di", $new_expense_limit, $user_id);

            if ($stmt->execute()) {
                header("Location: index.php?success=Expense limit updated successfully");
                exit();
            } else {
                $error = "Error updating expense limit: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Please provide a valid expense limit.";
        }
    }

    if ($expense_name && $expense_amount && $expense_date && $expense_category) {
        if (!empty($_POST['expense_id'])) {
            $expense_id = (int) $_POST['expense_id'];

            $stmt = $conn->prepare("UPDATE expenses SET expense_name = ?, expense_amount = ?, expense_date = ?, expense_category = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("sdssii", $expense_name, $expense_amount, $expense_date, $expense_category, $expense_id, $user_id);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Error updating expense: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO expenses (user_id, expense_name, expense_amount, expense_date, expense_category) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isdss", $user_id, $expense_name, $expense_amount, $expense_date, $expense_category);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Error adding expense: " . $stmt->error;
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
            <h1>Expense Tracker</h1>
        </header>
        <div class="income">
            <div class="totalIncome">
                <h3>Total Expenses</h3>
                <div class="incomeCard">
                    <div class="chartWrapper">
                        <canvas id="goalChart3"></canvas>
                        <div class="chartText"><p class="expense-amount">₹0</p></div>
                    </div>
                </div>
            </div>
            <div class="incomeChart">
                <h3>Expense Distribution</h3>
                <div class="incomeCard">
                    <canvas id="expenseCategoryChart"></canvas>
                </div>
            </div>
        </div>
        <button class="addBtn" onclick="openExpenseLimitForm()"><i class="fa-solid fa-bullseye"></i> Set Expense Limit</button>
        <div class="analytics">
            <h3>All Expenses</h3>
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
                        $stmt = $conn->prepare("SELECT * FROM expenses WHERE user_id = ?");
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['expense_name'] ?></td>
                            <td style="color:#FF4C4C"><?= $row['expense_amount'] ?></td>
                            <td><?= $row['expense_date'] ?></td>
                            <td><?= $row['expense_category'] ?></td>
                            <td><a href="javascript:void(0);" class="editBtn"
                                   data-id="<?= $row['id'] ?>"
                                   data-name="<?= $row['expense_name'] ?>"
                                   data-amount="<?= $row['expense_amount'] ?>"
                                   data-date="<?= $row['expense_date'] ?>"
                                   data-category="<?= $row['expense_category'] ?>"
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
            <h1>Add New Expense</h1>
            <button class="closeBtn" onclick="closeForm()"><i class="fa-solid fa-xmark"></i></button>
            <?php if (!empty($error)): ?>
            <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>
            <form method="POST" action="index.php">
                <div class="expName">
                    <label for="expName">Expense Name</label>
                    <input type="text" id="expName" name="expense_name" placeholder="Expense" required>
                </div>
                <div class="expAmt">
                    <label for="expAmt">Expense Amount</label>
                    <input type="number" id="expAmt" name="expense_amount" placeholder="Enter Amount" required>
                </div>
                <div class="expDate">
                    <label for="expDate">Expense Date</label>
                    <input type="date" id="expDate" name="expense_date" required>
                </div>
                <div class="expCat">
                    <label for="expCat">Expense Category</label>
                    <input type="text" id="expCat" name="expense_category" placeholder="Enter Category" required>
                </div>
                <button class="submitForm" type="submit">Submit</button>
            </form>
        </div>
    </div>
    <div class="editOverlay" id="editOverlay"></div>
    <div class="editForm" id="editForm">
        <div class="addFormCard">
            <h1>Edit Expense</h1>
            <button class="closeBtn" onclick="closeEditForm()"><i class="fa-solid fa-xmark"></i></button>
            <form method="POST" action="index.php">
                <input type="hidden" id="editId" name="expense_id">
                <div class="expName">
                    <label for="editExpName">Expense Name</label>
                    <input type="text" id="editExpName" name="expense_name" required>
                </div>
                <div class="expAmt">
                    <label for="editExpAmt">Expense Amount</label>
                    <input type="number" id="editExpAmt" name="expense_amount" required>
                </div>
                <div class="expDate">
                    <label for="editExpDate">Expense Date</label>
                    <input type="date" id="editExpDate" name="expense_date" required>
                </div>
                <div class="expCat">
                    <label for="editExpCat">Expense Category</label>
                    <input type="text" id="editExpCat" name="expense_category" required>
                </div>
                <button class="submitForm" type="submit">Save</button>
            </form>
        </div>
    </div>
    <div class="goalOverlay" id="expenseOverlay"></div>
    <div class="addGoalForm" id="expenseForm">
        <div class="addIncomeFormCard">
            <h1>Set Expense Limit</h1>
            <button class="closeBtn" onclick="closeExpenseForm()"><i class="fa-solid fa-xmark"></i></button>
            <form method="POST" action="index.php" id="expenseInputForm">
                <div class="expenseName">
                    <label for="expenseLimit">Expense Limit</label>
                    <input type="number" id="expenseLimit" name="expense_limit" required>
                </div>
                <button class="submitForm" type="submit">Set Limit</button>
            </form>
        </div>
    </div>
    </div>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const totalExpense = <?= json_encode($conn->query("SELECT SUM(expense_amount) as total FROM expenses WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0); ?>;
        const expenseLimitAmount = <?= json_encode($conn->query("SELECT expense_limit FROM users WHERE id = $user_id")->fetch_assoc()['expense_limit'] ?? 0); ?>;
    </script>
    <script src="charts.js"></script>
</body>
</html>