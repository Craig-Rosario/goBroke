function toggleSideBar() {
    const sidebar = document.querySelector('.sideBar');
    const button = document.querySelector('.toggleButton');

    sidebar.classList.toggle('collapsed');
    button.classList.toggle('collapsed');
}

function openForm() {
    document.getElementById("addForm").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

function closeForm() {
    document.getElementById("addForm").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}

function openEditForm(link) {
    // Get the expense data attributes from the clicked link
    const expenseId = link.getAttribute('data-id');
    const expenseName = link.getAttribute('data-name');
    const expenseAmount = link.getAttribute('data-amount');
    const expenseDate = link.getAttribute('data-date');
    const expenseCategory = link.getAttribute('data-category');

    // Set the data into the edit form fields
    document.getElementById('editId').value = expenseId;
    document.getElementById('editExpName').value = expenseName;
    document.getElementById('editExpAmt').value = expenseAmount;
    document.getElementById('editExpDate').value = expenseDate;
    document.getElementById('editExpCat').value = expenseCategory;

    // Show the edit form and overlay
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('editOverlay').style.display = 'block';
}

function closeEditForm() {
    document.getElementById('editForm').style.display = 'none';
    document.getElementById('editOverlay').style.display = 'none';
}
