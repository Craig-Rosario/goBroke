    document.addEventListener('DOMContentLoaded', function () {
        updateChart(totalIncome, goalAmount);
    });

    function updateChart(achievedIncome, userGoal) {
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

        if (window.chartInstance) {
            window.chartInstance.destroy();
        }

        window.chartInstance = new Chart(ctx1, {
            type: 'doughnut',
            data: chartData,
            options: chartOptions
        });

        document.querySelectorAll('.income-amount').forEach(el => {
            el.textContent = '₹' + achievedIncome.toLocaleString();
        });
    }

    // Call this after income deletion or update
    function updateAfterDelete(newAchievedIncomeValue) {
        updateChart(newAchievedIncomeValue, goalAmount);
    }

    // Optional: if you dynamically update `newAchievedIncomeValue` elsewhere
    if (typeof newAchievedIncomeValue !== 'undefined') {
        updateChart(newAchievedIncomeValue, goalAmount);
    }
