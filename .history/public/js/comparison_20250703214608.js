//比較計算
document.addEventListener('DOMContentLoaded', function () {
  // 計算ボタン＋値が変わったら再計算
  ['price_left', 'volume_left', 'unit_left', 'price_right', 'volume_right', 'unit_right'].forEach(id => {
    document.getElementById(id).addEventListener('change', runCalculation);
  });
  document.getElementById('calculate').addEventListener('click', runCalculation);

  // クリアボタン
  document.getElementById('clear-left').addEventListener('click', () => {
    document.getElementById('price_left').value = '';
    document.getElementById('volume_left').value = '';
    document.getElementById('unit_left').selectedIndex = 0;
    runCalculation(); // 入力値が消えた後に再計算！
  });

  document.getElementById('clear-right').addEventListener('click', () => {
    document.getElementById('price_right').value = '';
    document.getElementById('volume_right').value = '';
    document.getElementById('unit_right').selectedIndex = 0;
    runCalculation(); // 入力値が消えた後に再計算！
  });
});

//  計算ロジック関数
function runCalculation() {
  let priceLeft = parseFloat(document.getElementById('price_left').value);
  let volumeLeft = parseFloat(document.getElementById('volume_left').value);
  let unitLeft = document.getElementById('unit_left').value;

  let priceRight = parseFloat(document.getElementById('price_right').value);
  let volumeRight = parseFloat(document.getElementById('volume_right').value);
  let unitRight = document.getElementById('unit_right').value;

  // 単位変換
  let conversionRates = { "kg": 1000, "L": 1000, "m": 100 };
  volumeLeft = conversionRates[unitLeft] ? volumeLeft * conversionRates[unitLeft] : volumeLeft;
  volumeRight = conversionRates[unitRight] ? volumeRight * conversionRates[unitRight] : volumeRight;

  // 入力チェック（ゼロ除算防止）
  if (!volumeLeft || !volumeRight || !priceLeft || !priceRight) {
    document.getElementById('result').innerHTML = '';
    return;
  }

  // 単価＆お得度計算
  let unitPriceLeft = priceLeft / volumeLeft;
  let unitPriceRight = priceRight / volumeRight;
  let cheaper = unitPriceLeft < unitPriceRight ? "左側" : "右側";
  let savings = Math.abs(priceLeft - priceRight);

  // 結果表示
  document.getElementById('result').innerHTML = `
    <div class="alert alert-info mt-2">
      <p><strong>${cheaper}の方がお得！</strong></p>
      <p>左: ${unitPriceLeft.toFixed(2)} 円／単位</p>
      <p>右: ${unitPriceRight.toFixed(2)} 円／単位</p>
      <p>価格差: 約${savings.toFixed(2)}円</p>
    </div>
  `;
}