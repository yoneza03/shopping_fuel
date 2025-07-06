<h3>📈 燃費推移</h3>
<canvas id="fuelChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const labels = @json($labels);
  const data = @json($efficiencies);

  new Chart(document.getElementById('fuelChart'), {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: '燃費 (km/L)',
        data: data,
        borderColor: 'rgba(255, 99, 132, 1)',
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        tension: 0.4,
        pointRadius: 4
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: '燃費の推移'
        }
      }
    }
  });
});
</script>