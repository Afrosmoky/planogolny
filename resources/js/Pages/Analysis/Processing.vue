<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { usePage, Link } from '@inertiajs/vue3'
import { onMounted, onUnmounted, ref, computed } from 'vue'
import axios from 'axios'

import AnalysisLoader from '@/Components/AnalysisLoader.vue'

const loader = ref(null)

defineOptions({ layout: AppLayout })

defineProps({
    errors: Object,
    auth: Object,
    analysisId: Number,
    status: String,
})

// 🔹 REAKTYWNE PROPSY Z INERTIA (startowe)
const page = usePage()
const analysisId = computed(() => page.props.analysisId)

// 🔹 LOKALNY STATUS (sterowany pollingiem)
const status = ref(page.props.status)

// debug
console.log('analysisId:', analysisId.value)
console.log('initial status:', status.value)

let interval = null

function checkStatus() {
    axios
        .get(`/analysis/${analysisId.value}/status`)
        .then(response => {
            status.value = response.data.status
            if (status.value !== 'processing' ) {
                clearInterval(interval)
            }
        })
        .catch(() => {
            // opcjonalnie: obsługa błędu / timeout
        })
}

onMounted(() => {
    loader.value?.start()
    checkStatus()
    interval = setInterval(checkStatus, 5000)
})

onUnmounted(() => {
    clearInterval(interval)
    loader.value?.stop()
})

const acceptedTerms = ref(false)

const canProceed = computed(() => {
    return status.value === 'ready' && acceptedTerms.value === true
})
</script>

<template>
    <section class="wrapper pb-10">
        <div class="container text-center">
            <div class="row">
                <div
                    class="col-lg-7 col-xxl-6 mx-auto text-center"
                >
                    <div
                        class="d-flex justify-content-center mb-5 mb-md-0"
                    >

                        <div v-if="status === 'processing'" class="btn_top">
                            <h3>
<!--                                <img-->
<!--                                    src="/assets/img/check.svg"-->
<!--                                    class="check_ico"-->
<!--                                />-->
                                  &nbsp;&nbsp;&nbsp;Trwa wyszukiwanie Twojej działki<br>
                                  &nbsp;&nbsp;&nbsp;Prosimy nie zamykać okna przeglądarki
                            </h3>
                            <AnalysisLoader ref="loader" />
                        </div>

                        <div v-else-if="status === 'ready'" class="btn_top">
                            <h3>
                                <img
                                    src="/assets/img/check.svg"
                                    class="check_ico"
                                />&nbsp;&nbsp;&nbsp;Tak - System znalazł Twoją działkę
                            </h3>
                        </div>

                        <div v-else-if="status === 'found'" class="btn_top">
                            <h3>
                                <!-- <img
                                  src="./assets/img/check.svg"
                                  class="check_ico"
                                /> -->&nbsp;&nbsp;&nbsp;Nie znaleźliśmy działki na podstawie podanych danych
                            </h3>
                        </div>

                    </div>
                </div>
                <!--/column -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <!-- /section -->
    <section v-if="status === 'ready'" class="wrapper pb-14 pb-md-16">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="scroll-50 border p-4 bg-white mb-6">
                        <h2 class="mt-2">Regulamin serwisu Planogolny.info</h2>

                        <h4>§1. Postanowienia ogólne</h4>
                        <p>
                            Niniejszy Regulamin określa zasady świadczenia usług przez serwis
                            Planogolny.info, należący do spółki PTAHA Spółka z ograniczoną
                            odpowiedzialnością z siedzibą przy ul. Kieleckiej 2/50, 31-526 Kraków,
                            NIP: 6751814574, REGON: 541957673, KRS: 0001177010, zwaną dalej
                            „Usługodawcą”.
                        </p>
                        <p>
                            Serwis internetowy Planogolny.info umożliwia odpłatne wygenerowanie
                            raportów poglądowych określających prawdopodobieństwo możliwego
                            przeznaczenia danej działki w planie ogólnym na podstawie autorskiego
                            algorytmu Usługodawcy.
                        </p>
                        <p>
                            Każda osoba korzystająca z Serwisu, niezależnie od etapu procesu,
                            zobowiązana jest do przestrzegania niniejszego Regulaminu.
                        </p>

                        <h4>§2. Zakres usług</h4>
                        <p>
                            Usługa świadczona przez Serwis polega na udostępnieniu użytkownikowi,
                            po dokonaniu płatności, raportu poglądowego wskazującego przewidywane
                            przeznaczenie terenu w planie ogólnym.
                        </p>
                        <p>
                            Raport generowany jest automatycznie na podstawie lokalizacji
                            wskazanej przez użytkownika oraz w oparciu o autorski algorytm,
                            bazujący na danych przestrzennych, dokumentach planistycznych,
                            analizie otoczenia działki oraz danych statystycznych.
                        </p>
                        <p>
                            Informacje zawarte w raporcie mają charakter wyłącznie orientacyjny
                            i nie stanowią interpretacji przepisów prawa ani dokumentu urzędowego.
                        </p>

                        <ul>
                            <li>zabudowa mieszkalna – jednorodzinna</li>
                            <li>zabudowa mieszkalna – wielorodzinna</li>
                            <li>zabudowa usługowo-handlowa</li>
                            <li>zabudowa przemysłowa</li>
                            <li>tereny zielone, rolne, leśne i inne</li>
                        </ul>

                        <h4>§3. Ograniczenia i zrzeczenia</h4>
                        <p>
                            Serwis nie zastępuje urzędowych dokumentów, decyzji administracyjnych
                            ani zapisów planistycznych. Raport nie jest wiążący dla organów
                            administracji publicznej.
                        </p>
                        <p>
                            Usługodawca nie ponosi odpowiedzialności za straty materialne wynikłe
                            z późniejszych zmian przeznaczenia terenu ani za dane pochodzące
                            z zewnętrznych źródeł.
                        </p>
                        <p>
                            Użytkownik zobowiązany jest do samodzielnego zapoznania się
                            z projektem planu ogólnego w swojej gminie.
                        </p>

                        <h4>§4. Prawa autorskie</h4>
                        <p>
                            Cała zawartość Serwisu, w tym algorytmy, raporty i struktura danych,
                            stanowią własność PTAHA Sp. z o.o. i są chronione prawem autorskim.
                        </p>

                        <h4>§5. Płatności</h4>
                        <p>
                            Korzystanie z Serwisu jest odpłatne. Płatność realizowana jest za
                            pośrednictwem zintegrowanych systemów płatniczych.
                        </p>

                        <h4>§6. Reklamacje</h4>
                        <p>
                            Reklamacje należy zgłaszać w terminie 7 dni od otrzymania raportu
                            na adres: kontakt@planogolny.info.
                        </p>

                        <h4>§7. Ochrona danych osobowych</h4>
                        <p>
                            Administratorem danych osobowych jest PTAHA Sp. z o.o.
                            Szczegółowe informacje zawiera Polityka Prywatności.
                        </p>

                        <h4>§8. Postanowienia końcowe</h4>
                        <p>
                            Korzystanie z Serwisu oznacza akceptację Regulaminu oraz
                            Polityki Prywatności.
                        </p>

                        <hr class="my-5">

                        <h2>Polityka Prywatności</h2>

                        <h4>§1. Informacje ogólne</h4>
                        <p>
                            Niniejsza Polityka Prywatności określa zasady przetwarzania danych
                            osobowych użytkowników serwisu Planogolny.info, prowadzonego przez
                            PTAHA Sp. z o.o. z siedzibą w Krakowie.
                        </p>

                        <h4>§2. Administrator danych</h4>
                        <p>
                            Administratorem danych osobowych jest:
                        </p>
                        <p>
                            PTAHA Sp. z o.o.<br>
                            ul. Kielecka 2/50<br>
                            31-526 Kraków<br>
                            e-mail: kontakt@planogolny.info
                        </p>

                        <h4>§3. Cele i podstawy przetwarzania</h4>
                        <ul>
                            <li>realizacja usług – art. 6 ust. 1 lit. b RODO</li>
                            <li>obsługa reklamacji i kontakt – art. 6 ust. 1 lit. f RODO</li>
                            <li>obowiązki księgowe – art. 6 ust. 1 lit. c RODO</li>
                            <li>marketing – art. 6 ust. 1 lit. a RODO</li>
                        </ul>

                        <h4>§4. Zakres danych</h4>
                        <ul>
                            <li>imię i nazwisko</li>
                            <li>adres e-mail</li>
                            <li>adres IP</li>
                            <li>lokalizacja działki</li>
                            <li>dane cookies</li>
                        </ul>

                        <h4>§5. Okres przechowywania</h4>
                        <p>
                            Dane przechowywane są przez okres niezbędny do realizacji usługi
                            oraz obowiązków prawnych, a także do momentu cofnięcia zgody.
                        </p>

                        <h4>§6. Prawa użytkownika</h4>
                        <ul>
                            <li>dostęp do danych</li>
                            <li>sprostowanie danych</li>
                            <li>usunięcie danych</li>
                            <li>ograniczenie przetwarzania</li>
                            <li>wniesienie skargi do Prezesa UODO</li>
                        </ul>

                        <h4>§7. Cookies</h4>
                        <p>
                            Serwis wykorzystuje pliki cookies w celu prawidłowego działania
                            oraz analizy statystycznej.
                        </p>

                        <h4>§8. Zmiany Polityki</h4>
                        <p>
                            Polityka Prywatności może być aktualizowana. Nowa wersja będzie
                            publikowana w Serwisie.
                        </p>
                    </div>
                    <!--/.row -->
                </div>
                <!-- /column -->
                <div class="form-check mb-4 col-xl-10 mx-auto">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="acceptTerms"
                        v-model="acceptedTerms"
                    />
                    <label class="form-check-label" for="acceptTerms">
                        Oświadczam, że zapoznałem się z Regulaminem i Polityką Prywatności
                        oraz akceptuję ich treść
                    </label>
                </div>

                <div class="col-md-4 text-center mx-auto">
                    <Link
                        :href="route('payment.checkout', { analysis: analysisId })"
                        class="button_zielony mb-3 btn-primary"
                        :class="{ disabled: !canProceed }"
                        :aria-disabled="!canProceed"
                        :tabindex="canProceed ? 0 : -1"
                        @click.prevent="!canProceed"
                    >
                        Generuj raport »
                    </Link>
                </div>
                <!-- /column -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
</template>
