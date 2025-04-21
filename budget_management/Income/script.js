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
    const incomeId = link.getAttribute('data-id');
    const incomeName = link.getAttribute('data-name');
    const incomeAmount = link.getAttribute('data-amount');
    const incomeDate = link.getAttribute('data-date');
    const incomeCategory = link.getAttribute('data-category');

    document.getElementById('editId').value = incomeId;
    document.getElementById('editIncName').value = incomeName;
    document.getElementById('editIncAmt').value = incomeAmount;
    document.getElementById('editIncDate').value = incomeDate;
    document.getElementById('editIncCat').value = incomeCategory;

    document.getElementById('editForm').style.display = 'block';
    document.getElementById('editOverlay').style.display = 'block';
}

function closeEditForm() {
    document.getElementById('editForm').style.display = 'none';
    document.getElementById('editOverlay').style.display = 'none';
}

function openGoalForm() {
    document.getElementById("goalForm").style.display = "block";
    document.getElementById("goalOverlay").style.display = "block";
}

function closeGoalForm() {
    document.getElementById("goalForm").style.display = "none";
    document.getElementById("goalOverlay").style.display = "none";
}

document.querySelector(".addBtn").addEventListener("click", openGoalForm);  