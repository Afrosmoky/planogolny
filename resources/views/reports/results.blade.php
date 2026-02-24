<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/planogolny.css" />
    <link rel="stylesheet" href="/assets/css/plugins.css" />
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="stylesheet" href="/assets/css/colors/navy.css" />
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
<body class="bg_all">

<header class="wrapper pb-13">
    <div class="container">
        <div class="row">
            <div class="col-xl-10 mx-auto">
                <div
                    class="row justify-content-center justify-content-md-between"
                >
                    <div class="col-11 col-md-5 mt-7 align-self-center">
                        <img src="/assets/img/top_l.svg" class="img-fluid" />
                    </div>
                    <div class="col-11 col-md-5 mt-7 align-self-center">
                        <img src="/assets/img/top_r.svg" class="img-fluid" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="wrapper pb-14 pb-md-16">

    <div class="container text-center">
        <div class="row pb-10">
            <div
                class="col-lg-7 col-xxl-6 mx-auto text-center"

            >
                <div
                    class="d-flex justify-content-center mb-5 mb-md-0"

                >

                    <h1>Raport planistyczny nr: {{ $order->report_number }}</h1>

                    <div class="box">
                        <strong>Analizowany adres:</strong><br>
                        {{ $order->analysis->address }}
                    </div>

                    <div class="box">
                        <strong>Analizowane współrzędne:</strong><br>
                        {{ $order->analysis->lat }},
                        {{ $order->analysis->lng }}

                    </div>

                    <div class="box">
                        Raport ma charakter informacyjny i nie stanowi decyzji administracyjnej.
                    </div>
                    @php
                        $labels = [
                            'residential_single' => 'Zabudowa mieszkaniowa jednorodzinna',
                            'residential_multi'  => 'Zabudowa mieszkaniowa wielorodzinna',
                            'service'            => 'Zabudowa usługowo-handlowa',
                            'industrial'         => 'Zabudowa przemysłowa',
                            'green'              => 'Tereny zielone / rolne / inne',
                        ];
                    @endphp
                    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-2">
                        <h2>Prawdopodobieństwo przeznaczenia terenu w Planie Ogólnym</h2>

                        <table>
                            @foreach ($landUseProbabilities as $key => $percent)
                                <tr>
                                    <td>{{ $labels[$key] ?? $key }}</td>
                                    <td>{{ $percent }}%</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-2">
                        <h2>Otoczenie działki</h2>

                        <p>
                            {{ $surroundings->developmentDescription }}
                        </p>

                        @if (!empty($surroundings->bulletPoints))
                            <ul>
                                @foreach ($surroundings->bulletPoints as $point)
                                    <li>{{ $point }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <p>
                            <strong>Podsumowanie:</strong>
                            {{ $surroundings->summary }}
                        </p>
                    </div>

                    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-2">
                        <h2>Ograniczenia wynikające z przepisów odrębnych</h2>
                        <p>
                            Poniższa analiza wskazuje na uwarunkowania wynikające z obowiązujących
                            przepisów prawa, które mogą wpływać na sposób zagospodarowania terenu
                            niezależnie od ustaleń planu ogólnego.
                        </p>
                        @if ($legalConstraints->hasAnyRestrictions)
                            <ul>
                                @foreach ($legalConstraints->bulletPoints as $point)
                                    <li>{{ $point }}</li>
                                @endforeach
                            </ul>

                            @if (!empty($legalConstraints->legalBasis))
                                <p><strong>Podstawa prawna:</strong></p>
                                <ul>
                                    @foreach ($legalConstraints->legalBasis as $basis)
                                        <li>{{ $basis }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        @else
                            <p>
                                Nie stwierdzono istotnych ograniczeń wynikających z przepisów odrębnych
                                w bezpośrednim otoczeniu działki.
                            </p>
                        @endif

                        <p>
                            <strong>Podsumowanie:</strong>
                            {{ $legalConstraints->summary }}
                        </p>

                    </div>

                    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-2">
                        <h2>{{ $finalSummary->headline }}</h2>

                        <p>
                            {{ $finalSummary->body }}
                        </p>

                        <p>
                            <strong>
                                {{ $finalSummary->callToAction }}
                            </strong>
                        </p>
                    </div>
                    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-2">
                        <p>Zastrzeżenie: Raport nie jest dokumentem urzędowym i nie przesądza o przeznaczeniu terenu.
                            Przedstawia wyłącznie możliwe scenariusze wygenerowane algorytmicznie na podstawie danych publicznych;
                            wiążące ustalenia wynikają wyłącznie z aktów planistycznych właściwych organów.</p>


                        <p>Korzystanie z raportu odbywa się na wyłączną odpowiedzialność Użytkownika i nie zwalnia go z obowiązku samodzielnej weryfikacji informacji w urzędzie gminy lub miasta.</p>
                    </div>

{{--                    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-2">--}}
{{--                        <div>Zabudowa jednorodzinna: <strong>50%</strong></div>--}}
{{--                        <div>Wielorodzinna: <strong>20%</strong></div>--}}
{{--                        <div>Usługowa: <strong>10%</strong></div>--}}
{{--                        <div>Przemysłowa: <strong>0%</strong></div>--}}
{{--                        <div>Zielone / rolne: <strong>20%</strong></div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>
