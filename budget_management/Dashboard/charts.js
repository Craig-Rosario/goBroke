document.addEventListener('DOMContentLoaded', function () {
    fetchDashboardData();
});

function fetchDashboardData() {
    Promise.all([
        fetch('../Income/fetch_income_categories.php').then(response => response.json()),
        fetch('../Expenses/fetch_expense_categories.php').then(response => response.json()),
        fetch('../Income/fetch_total_income.php').then(response => response.json()), // New fetch for total income
        fetch('../Expenses/fetch_total_expense.php').then(response => response.json()) // New fetch for total expense
    ])
    .then(([incomeData, expenseData, totalIncomeData, totalExpenseData]) => {
        renderIncomeCategoryChart(incomeData);
        renderExpenseCategoryChart(expenseData);
        const totalIncome = parseFloat(totalIncomeData.total) || 0;
        const totalExpense = parseFloat(totalExpenseData.total) || 0;
        const savingsAmount = totalIncome - totalExpense;
        updateSavingsCard(totalIncome, savingsAmount);
    })
    .catch(error => {
        console.error('Error fetching dashboard data:', error);
    });
}

function updateSavingsCard(totalIncome, savingsAmount) {
    const remaining = Math.max(totalIncome - savingsAmount, 0); // 'remaining' might not be relevant now
    const formattedSavings = (savingsAmount < 0 ? '-₹' : '₹') + Math.abs(savingsAmount).toLocaleString();

    const chartData = {
        datasets: [{
            data: [Math.abs(savingsAmount), Math.max(0 - savingsAmount, 0)], // Use absolute value for chart data
            backgroundColor: savingsAmount >= 0 ? ['#00FF7F', '#f0f0f0'] : ['#FF4C4C', '#f0f0f0'], // Green if positive, red if negative
            borderWidth: 0
        }]
    };

    const chartOptions = {
        rotation: -90,
        circumference: 180,
        cutout: '75%',
        plugins: {
            legend: {
                display: false
            }
        }
    };

    const ctx = document.getElementById('goalChart').getContext('2d');

    if (window.savingsChartInstance) {
        window.savingsChartInstance.destroy();
    }

    window.savingsChartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: chartData,
        options: chartOptions
    });

    document.getElementById('chartText').textContent = formattedSavings;
}

function renderIncomeCategoryChart(data) { // Modified to accept data
    const ctx = document.getElementById('incomeChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.amounts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#333'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (context.parsed !== null) {
                                label += ': ₹' + context.parsed.toLocaleString();
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
}

function renderExpenseCategoryChart(data) { // Modified to accept data
    const ctx = document.getElementById('expenseChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.amounts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#333'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (context.parsed !== null) {
                                label += ': ₹' + context.parsed.toLocaleString();
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
}