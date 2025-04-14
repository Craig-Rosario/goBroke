document.addEventListener('DOMContentLoaded', function () {
    const initialAchieved = totalIncome; 
    updateChart(initialAchieved);  
});

function updateChart(achievedIncome) {
    const goal = achievedIncome * 1.25;
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

    window.chartInstance = new Chart(ctx1, { type: 'doughnut', data: chartData, options: chartOptions });

    document.querySelectorAll('.income-amount').forEach(el => {
        el.textContent = '₹' + achievedIncome.toLocaleString();
    });
}

function updateAfterDelete(newAchievedIncomeValue) {
    updateChart(newAchievedIncomeValue);
}

let updatedTotalIncome = newAchievedIncomeValue;  
updateChart(updatedTotalIncome);  
