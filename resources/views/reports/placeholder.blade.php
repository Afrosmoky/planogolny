<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h1 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .box {
            border: 1px solid #ddd;
            padding: 12px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<h1>Raport planistyczny</h1>

<div class="box">
    <strong>Adres:</strong><br>
    {{ $order->address_text }}
</div>

<div class="box">
    <strong>Status raportu:</strong><br>
    Wersja robocza (beta)
</div>

<div class="box">
    Raport ma charakter informacyjny i nie stanowi decyzji administracyjnej.
</div>

<div class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-2">
    <div>Zabudowa jednorodzinna: <strong>50%</strong></div>
    <div>Wielorodzinna: <strong>20%</strong></div>
    <div>Usługowa: <strong>10%</strong></div>
    <div>Przemysłowa: <strong>0%</strong></div>
    <div>Zielone / rolne: <strong>20%</strong></div>
</div>

</body>
</html>
