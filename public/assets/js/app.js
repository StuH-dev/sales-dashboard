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
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: 'Budget',
                    data: budget,
                    borderDash: [6, 4],
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: 'Target',
                    data: target,
                    borderDash: [2, 2],
                    borderWidth: 2,
                    tension: 0.3
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
