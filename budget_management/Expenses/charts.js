document.addEventListener('DOMContentLoaded', function () {
    updateExpenseChart(totalExpense, expenseLimitAmount);
    renderExpenseCategoryChart();
});

function updateExpenseChart(spentAmount, limitAmount) {
    const remaining = Math.max(limitAmount - spentAmount, 0);

    const chartData = {
        datasets: [{
            data: [spentAmount, remaining],
            backgroundColor: ['#FF6B6B', '#f0f0f0'], // Red and light gray colors for segments
            borderColor: [
                '#E14D4D',  // Darker neon green for border
                '#d0d0d0'   // Darker light gray for border
            ],
            borderWidth: 3,  // Prominent border around segments
            hoverBorderWidth: 6,  // Glow effect on hover
            hoverBorderColor: [
                '#E14D4D',  // Darker neon green on hover
                '#d0d0d0'   // Darker light gray on hover
            ],
            hoverOffset: 10,  // Slight offset effect on hover
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
                            'rgba(105, 179, 105, 0.8)',    
                            'rgba(127, 127, 237, 0.8)',      
                            'rgba(255, 140, 0, 0.8)',      
                            'rgba(50, 148, 148, 0.8)',    
                            'rgba(148, 72, 58, 0.8)',      
                            'rgba(148, 0, 211, 0.8)'       
                        ],
                        borderColor: [
                            'rgba(105, 179, 105, 0.8)',    
                            'rgba(127, 127, 237, 0.8)',      
                            'rgba(255, 140, 0, 0.8)',      
                            'rgba(50, 148, 148, 0.8)',     
                            'rgba(148, 72, 58, 0.8)',    
                            'rgba(148, 0, 211, 0.8)'      
                        ],
                        borderWidth: 3, 
                        hoverBorderWidth: 6, 
                        hoverBorderColor: [
                            'rgba(105, 179, 105, 0.8)',    
                            'rgba(127, 127, 237, 0.8)',     
                            'rgba(255, 140, 0, 0.8)',      
                            'rgba(50, 148, 148, 0.8)',    
                            'rgba(148, 72, 58, 0.8)',      
                            'rgba(148, 0, 211, 0.8)'       
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