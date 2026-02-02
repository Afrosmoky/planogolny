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
    <strong>Informacja dodatkowa</strong><br>
    Do wiadomości dołączono również przykładowe wzory pism, które mogą być pomocne
    w przypadku podejmowania dalszych działań formalnych, w szczególności w zakresie:
</p>

<ul>
    <li>wystąpienia o ustalenie warunków zabudowy lub lokalizacji inwestycji celu publicznego,</li>
    <li>składania wniosków lub uwag dotyczących aktów planowania przestrzennego.</li>
</ul>

<p>
    Wzory dokumentów mają charakter <strong>pomocniczy i informacyjny</strong>.
    Ich wykorzystanie nie jest obowiązkowe i nie stanowi porady prawnej ani administracyjnej.
    Zaleca się każdorazowo dostosowanie treści pism do indywidualnej sytuacji
    oraz wymagań właściwego urzędu.
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
