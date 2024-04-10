<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $expense->description }}</title>
</head>
<body>
    <h2>{{ $expense->description }}</h2>

    <p>Uma nova despesa foi criada com sucesso:</p>

    <ul>
        <li><strong>Valor:</strong> R$ {{ number_format($expense->amount, 2, ',', '.') }}</li>
        <li><strong>Descrição:</strong> {{ $expense->description }}</li>
    </ul>

</body>
</html>
