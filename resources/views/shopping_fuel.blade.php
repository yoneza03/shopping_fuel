<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Shopping Fuel</title>
</head>
<body>
    <h1>Fuel Prices</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
        </tr>
        @foreach ($fuels as $fuel)
        <tr>
            <td>{{ $fuel->id }}</td>
            <td>{{ $fuel->name }}</td>
            <td>{{ $fuel->price }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>