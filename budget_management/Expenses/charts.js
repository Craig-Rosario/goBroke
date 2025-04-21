document.addEventListener('DOMContentLoaded', function () {
    updateExpenseChart(totalExpense, expenseLimitAmount);
    renderExpenseCategoryChart();
});

function updateExpenseChart(spentAmount, limitAmount) {
    const remaining = Math.max(limitAmount - spentAmount, 0);

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

    window.expenseChartInstance = new Chart(ctx2, {
        type: 'doughnut',
        data: chartData,
        options: chartOptions
    });

    document.querySelectorAll('.expense-amount').forEach(el => {
        el.textContent = '₹' + spentAmount.toLocaleString();
    });
}

function updateAfterExpenseDelete(newExpenseValue) {
    updateExpenseChart(newExpenseValue, expenseLimitAmount);
}

if (typeof updatedTotalExpense !== 'undefined') {
    updateExpenseChart(updatedTotalExpense, expenseLimitAmount);
}

function renderExpenseCategoryChart() {
    fetch('fetch_expense_categories.php')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('expenseCategoryChart').getContext('2d');
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
                                color: '#fff'
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
        });
}