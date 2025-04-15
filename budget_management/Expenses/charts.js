document.addEventListener('DOMContentLoaded', function () {
    const initialSpent = totalExpense;
    updateExpenseChart(initialSpent);  
});

function updateExpenseChart(spentAmount) {
    const goal = spentAmount * 1.25;
    const remaining = Math.max(goal - spentAmount, 0);

    const chartData = {
        datasets: [{
            data: [spentAmount, remaining],
            backgroundColor: ['#FF6B6B', '#f0f0f0'],
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

    const ctx2 = document.getElementById('goalChart3').getContext('2d');

    if (window.expenseChartInstance) {
        window.expenseChartInstance.destroy();  
    }

    window.expenseChartInstance = new Chart(ctx2, { type: 'doughnut', data: chartData, options: chartOptions });

    document.querySelectorAll('.expense-amount').forEach(el => {
        el.textContent = '₹' + spentAmount.toLocaleString();
    });
}

function updateAfterExpenseDelete(newExpenseValue) {
    updateExpenseChart(newExpenseValue);
}

let updatedTotalExpense = totalExpense;  
updateExpenseChart(updatedTotalExpense);
