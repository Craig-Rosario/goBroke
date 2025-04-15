document.addEventListener('DOMContentLoaded', function () {
    
    const savings = 50000; 
    const income = 100000;  

    createSavingsChart(savings, income);
});

function createSavingsChart(savings, income) {
    const remaining = Math.max(income - savings, 0);  

    const chartData = {
        datasets: [{
            data: [savings, remaining],  
            backgroundColor: ['#FFD700', '#f0f0f0'],  
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

    const difference = income - savings;
    document.getElementById('chartText').textContent = '₹' + difference.toLocaleString();
}
