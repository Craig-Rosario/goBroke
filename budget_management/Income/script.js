function toggleSideBar() {
    const sidebar = document.querySelector('.sideBar');
    const button = document.querySelector('.toggleButton');

    sidebar.classList.toggle('collapsed');
    button.classList.toggle('collapsed');
}

function openForm() {
    document.querySelector('.addIncomeForm').style.display = "block";
    document.querySelector('.incomeOverlay').style.display = "block";
}

function closeForm() {
    document.querySelector('.addIncomeForm').style.display = "none";
    document.querySelector('.incomeOverlay').style.display = "none";
}

function openEditForm() {
    document.querySelector('.editIncomeForm').style.display = "block";
    document.getElementById('editOverlay').style.display = "block";
}

function closeEditForm() {
    document.querySelector('.editIncomeForm').style.display = "none";
    document.getElementById('editOverlay').style.display = "none";
}
