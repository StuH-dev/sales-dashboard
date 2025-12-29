document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('rollingChart');
    if (!canvas) return;

    const labels   = JSON.parse(canvas.dataset.labels || '[]');
    const invoiced = JSON.parse(canvas.dataset.invoiced || '[]');
    const budget   = JSON.parse(canvas.dataset.budget || '[]');
    const target   = JSON.parse(canvas.dataset.target || '[]');

    // eslint-disable-next-line no-undef
    new Chart(canvas, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Invoiced',
                    data: invoiced,
                    borderColor: '#4C6FFF',
                    backgroundColor: 'rgba(76, 111, 255, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Budget',
                    data: budget,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderDash: [6, 4],
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Target',
                    data: target,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderDash: [2, 2],
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    ticks: {
                        callback: value => '$' + value.toLocaleString()
                    }
                }
            }
        }
    });
});
