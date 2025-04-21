<?php
session_start();
include("../Registration/database.php");

$error = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM incomes WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $income_name = $_POST['income_name'] ?? '';
    $income_amount = $_POST['income_amount'] ?? '';
    $income_date = $_POST['income_date'] ?? '';
    $income_category = $_POST['income_category'] ?? '';

    if (isset($_POST['income_id']) && !empty($_POST['income_id'])) {
        $income_id = $_POST['income_id'];
        if ($income_name && $income_amount && $income_date && $income_category) {
            $stmt = $conn->prepare("UPDATE incomes SET income_name = ?, income_amount = ?, income_date = ?, income_category = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("sdssii", $income_name, $income_amount, $income_date, $income_category, $income_id, $user_id);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Please fill all fields.";
        }
    } else {
        if ($income_name && $income_amount && $income_date && $income_category) {
            $stmt = $conn->prepare("INSERT INTO incomes (user_id, income_name, income_amount, income_date, income_category) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issds", $user_id, $income_name, $income_amount, $income_date, $income_category);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Please fill all fields.";
        }
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
                <h3>Income Chart</h3>
                <div class="incomeCard">
                    <canvas id="goalChart2"></canvas>
                    <p class="income-amount">₹5,432,000</p>
                </div>
            </div>
        </div>

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
                            <td><?= $row['income_amount'] ?></td>
                            <td><?= $row['income_date'] ?></td>
                            <td><?= $row['income_category'] ?></td>
                            <td><a href="javascript:void(0);" class="editBtn"
                                   data-id="<?= $row['id'] ?>"
                                   data-name="<?= $row['income_name'] ?>"
                                   data-amount="<?= $row['income_amount'] ?>"
                                   data-date="<?= $row['income_date'] ?>"
                                   data-category="<?= $row['income_category'] ?>"
                                   onclick="openEditForm(this)"><i style="color:white;" class="fa-solid fa-pen-to-square"></i></a></td>
                            <td><a href="index.php?delete_id=<?= $row['id'] ?>"><i style="color:#FF4C4C" class="fa-solid fa-trash"></i></a></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="incomeOverlay" id="incomeOverlay"></div>

    <div class="addIncomeForm" id="addForm">
        <div class="addIncomeFormCard">
            <h1>Add New Income</h1>
            <button class="closeBtn" onclick="closeForm()"><i class="fa-solid fa-xmark"></i></button>
            <?php if (!empty($error)): ?>
            <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>
            <form method="POST" action="index.php">
                <div class="incomeName">
                    <label for="incomeName">Income Name</label>
                    <input type="text" id="incomeName" name="income_name" required>
                </div>
                <div class="incomeAmt">
                    <label for="incomeAmt">Income Amount</label>
                    <input type="number" id="incomeAmt" name="income_amount" required>
                </div>
                <div class="incomeDate">
                    <label for="incomeDate">Income Date</label>
                    <input type="date" id="incomeDate" name="income_date" required>
                </div>
                <div class="incomeCat">
                    <label for="incomeCat">Income Category</label>
                    <input type="text" id="incomeCat" name="income_category" required>
                </div>
                <button class="submitForm" type="submit">Submit</button>
            </form>
        </div>
    </div>

    <div class="editIncomeOverlay" id="editOverlay"></div>

    <div class="editIncomeForm" id="editForm">
        <div class="addIncomeFormCard">
            <h1>Edit Income</h1>
            <button class="closeBtn" onclick="closeEditForm()"><i class="fa-solid fa-xmark"></i></button>
            <form method="POST" action="index.php">
                <input type="hidden" id="editId" name="income_id">
                <div class="incomeName">
                    <label for="editIncomeName">Income Name</label>
                    <input type="text" id="editIncomeName" name="income_name" required>
                </div>
                <div class="incomeAmt">
                    <label for="editIncomeAmt">Income Amount</label>
                    <input type="number" id="editIncomeAmt" name="income_amount" required>
                </div>
                <div class="incomeDate">
                    <label for="editIncomeDate">Income Date</label>
                    <input type="date" id="editIncomeDate" name="income_date" required>
                </div>
                <div class="incomeCat">
                    <label for="editIncomeCat">Income Category</label>
                    <input type="text" id="editIncomeCat" name="income_category" required>
                </div>
                <button class="submitForm" type="submit">Save</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const totalIncome = <?= json_encode($conn->query("SELECT SUM(income_amount) as total FROM incomes WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0); ?>;
    </script>
    <script src="charts.js"></script>
</body>
</html>
