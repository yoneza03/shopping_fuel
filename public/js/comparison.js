document.getElementById('calculate').addEventListener('click', function () {
    // 入力値を取得
    let priceLeft = parseFloat(document.getElementById('price_left').value);
    let volumeLeft = parseFloat(document.getElementById('volume_left').value);
    let unitLeft = document.getElementById('unit_left').value;

    let priceRight = parseFloat(document.getElementById('price_right').value);
    let volumeRight = parseFloat(document.getElementById('volume_right').value);
    let unitRight = document.getElementById('unit_right').value;

    // 単位を統一 (ここではg/ml/cmへ変換する例)
    let conversionRates = { "kg": 1000, "L": 1000, "m": 100 };
    volumeLeft = conversionRates[unitLeft] ? volumeLeft * conversionRates[unitLeft] : volumeLeft;
    volumeRight = conversionRates[unitRight] ? volumeRight * conversionRates[unitRight] : volumeRight;

    // 単価計算
    let unitPriceLeft = priceLeft / volumeLeft;
    let unitPriceRight = priceRight / volumeRight;
    
    // お得度計算
    let cheaper = unitPriceLeft < unitPriceRight ? "左側" : "右側";
    let savings = Math.abs(priceLeft - priceRight);

    // 結果表示
    document.getElementById('result').innerHTML = `<strong>${cheaper}の方が約${savings.toFixed(2)}円お得!</strong>`;
});
