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
            backgroundColor: [
                '#00FF7F',  // Neon green
                '#f0f0f0'   // Light gray
            ],
            borderColor: [
                '#00CC66',  // Darker neon green for border
                '#d0d0d0'   // Darker light gray for border
            ],
            borderWidth: 3,  // Prominent border around segments
            hoverBorderWidth: 6,  // Glow effect on hover
            hoverBorderColor: [
                '#00CC66',  // Darker neon green on hover
                '#d0d0d0'   // Darker light gray on hover
            ],
            hoverOffset: 10,  // Creates space between segments on hover
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
                            'rgba(0, 255, 255, 0.8)',  // Neon cyan
                            'rgba(255, 165, 0, 0.8)',  // Neon orange
                            'rgba(255, 105, 180, 0.8)',  // Neon pink
                            'rgba(0, 191, 255, 0.8)',  // Neon blue
                            'rgba(138, 43, 226, 0.8)',  // Neon blue-violet
                            'rgba(255, 69, 0, 0.8)'   // Neon red-orange
                        ],
                        borderColor: [
                            'rgba(0, 255, 255, 1)',  // Neon cyan
                            'rgba(255, 165, 0, 1)',  // Neon orange
                            'rgba(255, 105, 180, 1)',  // Neon pink
                            'rgba(0, 191, 255, 1)',  // Neon blue
                            'rgba(138, 43, 226, 1)',  // Neon blue-violet
                            'rgba(255, 69, 0, 1)'   // Neon red-orange
                        ],
                        borderWidth: 3, // Make borders more prominent
                        hoverBorderWidth: 6, // Increase hover border width
                        hoverBorderColor: [
                            'rgba(0, 255, 255, 0.8)',  // Neon cyan
                            'rgba(255, 165, 0, 0.8)',  // Neon orange
                            'rgba(255, 105, 180, 0.8)',  // Neon pink
                            'rgba(0, 191, 255, 0.8)',  // Neon blue
                            'rgba(138, 43, 226, 0.8)',  // Neon blue-violet
                            'rgba(255, 69, 0, 0.8)'   // Neon red-orange
                        ],
                        hoverOffset: 10,
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