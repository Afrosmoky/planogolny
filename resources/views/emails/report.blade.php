<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
</head>
<body>

<p>Dzień dobry,</p>

<p>
    W załączniku przesyłamy raport planistyczny dla wskazanej lokalizacji:<br>

    Adres nieruchomości: {{ $order->analysis->address }}<br/>

    Współrzędne lokalizacji nieruchomości: {{ $order->analysis->lat }},
    {{ $order->analysis->lng }}
</p>

<p>
    W załączniku znajduje się również faktura zakupu raportu.
</p>

<p>
    Dziękujemy za skorzystanie z serwisu <strong>PlanOgólny.info</strong>.
</p>

<p>
    <small>
        Wiadomość została wygenerowana automatycznie.
    </small>
</p>

</body>
</html>
