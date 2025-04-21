// Toggle sidebar visibility
function toggleSideBar() {
    const sidebar = document.querySelector('.sideBar');
    const button = document.querySelector('.toggleButton');

    sidebar.classList.toggle('collapsed');
    button.classList.toggle('collapsed');
}

// Open the add form with overlay
function openForm() {
    document.getElementById("addForm").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

// Close the add form and overlay
function closeForm() {
    document.getElementById("addForm").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}

// Open the edit form with prefilled values for expense
function openEditForm(link) {
    const expenseId = link.getAttribute('data-id');
    const expenseName = link.getAttribute('data-name');
    const expenseAmount = link.getAttribute('data-amount');
    const expenseDate = link.getAttribute('data-date');
    const expenseCategory = link.getAttribute('data-category');

    // Prefill the edit form with the current expense data
    document.getElementById('editId').value = expenseId;
    document.getElementById('editExpName').value = expenseName;
    document.getElementById('editExpAmt').value = expenseAmount;
    document.getElementById('editExpDate').value = expenseDate;
    document.getElementById('editExpCat').value = expenseCategory;

    // Display the edit form with overlay
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('editOverlay').style.display = 'block';
}

// Close the edit form and overlay
function closeEditForm() {
    document.getElementById('editForm').style.display = 'none';
    document.getElementById('editOverlay').style.display = 'none';
}


function openExpenseLimitForm() {
    document.getElementById("expenseForm").style.display = "block";
    document.getElementById("expenseOverlay").style.display = "block";
}

function closeExpenseForm() {
    document.getElementById("expenseForm").style.display = "none";
    document.getElementById("expenseOverlay").style.display = "none";
}

// Event listener for close button
document.getElementById("closeExpenseBtn").addEventListener("click", closeExpenseForm);

// This line below should already exist for opening the form, but to be sure:
document.querySelector(".addBtn").addEventListener("click", openExpenseLimitForm);