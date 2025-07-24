<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レシートアップロード</title>
</head>
<body>
    <h2>📸 レシート画像アップロード</h2>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form method="POST" action="/upload-receipt" enctype="multipart/form-data">
        @csrf
        <input type="file" name="receipt_image" accept="image/*" capture="environment" required>
        <button type="submit">アップロード</button>
    </form>
</body>
</html>