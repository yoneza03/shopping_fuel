@section('scripts')
<script>
document.getElementById('calc-btn').addEventListener('click', () => {
  const dist = parseFloat(document.getElementById('distance').value);
  const fuel = parseFloat(document.getElementById('fuel').value);
  const resultEl = document.getElementById('result');

  if (!isNaN(dist) && !isNaN(fuel) && fuel !== 0) {
    const mileage = (dist / fuel).toFixed(2);
    resultEl.value = `${mileage} km/L`;
  } else {
    resultEl.value = '計算できません';
  }
});
</script>
@endsection