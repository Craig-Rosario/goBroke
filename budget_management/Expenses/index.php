<?php
session_start();
include("../Registration/database.php");

$error = "";

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $user_id = $_SESSION['user_id']; 

    $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);

    if ($stmt->execute()) {
        $totalExpense = $conn->query("SELECT SUM(expense_amount) as total FROM expenses WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0;
        echo "<script>const totalExpense = " . json_encode($totalExpense) . ";</script>";
        header("Location: index.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../Login/login.php");
        exit();
    }

    $user_id = $_SESSION['user_id']; 

    if (isset($_POST['expense_id']) && !empty($_POST['expense_id'])) {
        $expense_id = $_POST['expense_id'];
        $expense_name = $_POST['expense_name'];
        $expense_amount = $_POST['expense_amount'];
        $expense_date = $_POST['expense_date'];
        $expense_category = $_POST['expense_category'];

        if (!empty($expense_name) && !empty($expense_amount) && !empty($expense_date) && !empty($expense_category)) {
            $stmt = $conn->prepare("UPDATE expenses SET expense_name = ?, expense_amount = ?, expense_date = ?, expense_category = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("sdssii", $expense_name, $expense_amount, $expense_date, $expense_category, $expense_id, $user_id);

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
        $expense_name = $_POST['expense_name'];
        $expense_amount = $_POST['expense_amount'];
        $expense_date = $_POST['expense_date'];
        $expense_category = $_POST['expense_category'];

        if (!empty($expense_name) && !empty($expense_amount) && !empty($expense_date) && !empty($expense_category)) {
            $stmt = $conn->prepare("INSERT INTO expenses (user_id, expense_name, expense_amount, expense_date, expense_category) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issds", $user_id, $expense_name, $expense_amount, $expense_date, $expense_category);

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

        <div class="income"> <!-- Consider renaming this class to "expenses" if you want cleaner structure -->
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
                <h3>Expenses Chart</h3>
                <div class="incomeCard">
                    <canvas id="goalChart4"></canvas>
                    <p class="expense-amount">₹0</p>
                </div>
            </div>
        </div>

        
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
                        $user_id = $_SESSION['user_id']; 

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
                                onclick="openEditForm(this)">Edit</a></td>

                            <td><a href="index.php?delete_id=<?= $row['id'] ?>" style="color: red;"><i class="fa-solid fa-trash"></i></a></td>
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



    </div>





    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const totalExpense = <?= json_encode($conn->query("SELECT SUM(expense_amount) as total FROM expenses WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0); ?>;
    </script>
    <script src="charts.js"></script>
</body>

</html>