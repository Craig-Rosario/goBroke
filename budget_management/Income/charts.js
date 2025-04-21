document.addEventListener('DOMContentLoaded', function () {
    updateIncomeChart(totalIncome, goalAmount);
    renderIncomeCategoryChart();
});

function updateIncomeChart(achievedIncome, userGoal) {
    const goal = userGoal;
    const remaining = Math.max(goal - achievedIncome, 0);

    const chartData = {
        datasets: [{
            data: [achievedIncome, remaining],
            backgroundColor: ['#00FF7F', '#f0f0f0'],
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

    const ctx1 = document.getElementById('goalChart1').getContext('2d');

    if (window.incomeChartInstance) {
        window.incomeChartInstance.destroy();
    }

    window.incomeChartInstance = new Chart(ctx1, {
        type: 'doughnut',
        data: chartData,
        options: chartOptions
    });

    document.querySelectorAll('.income-amount').forEach(el => {
        el.textContent = '₹' + achievedIncome.toLocaleString();
    });
}

function updateAfterDelete(newAchievedIncomeValue) {
    updateIncomeChart(newAchievedIncomeValue, goalAmount);
}

if (typeof newAchievedIncomeValue !== 'undefined') {
    updateIncomeChart(newAchievedIncomeValue, goalAmount);
}

function renderIncomeCategoryChart() {
    fetch('fetch_income_categories.php')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('incomeCategoryChart').getContext('2d');
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