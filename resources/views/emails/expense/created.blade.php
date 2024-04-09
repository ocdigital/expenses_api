<!-- resources/views/emails/expense/created.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Despesa Criada</title>
</head>
<body>
    <h2>Despesa Criada</h2>

    <p>Uma nova despesa foi criada com sucesso:</p>

    <ul>
        <li><strong>Valor:</strong> R$ {{ number_format($expense->amount, 2, ',', '.') }}</li>
        <li><strong>Descrição:</strong> {{ $expense->description }}</li>
        <!-- Adicione mais informações sobre a despesa aqui, se necessário -->
    </ul>

    <p>Atenciosamente,<br>Seu Aplicativo</p>
</body>
</html>
