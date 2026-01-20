<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { onMounted, onUnmounted, ref } from 'vue'
import axios from 'axios'

defineOptions({ layout: AppLayout })

const props = defineProps({
    orderId: Number,
    status: String,
    report: Object,
})

const paymentStatus = ref(props.status)
let interval = null
let attempts = 0
const MAX_ATTEMPTS = 24

const checkStatus = async () => {
    attempts++
    if (attempts > MAX_ATTEMPTS) {
        clearInterval(interval)
        return
    }

    try {
        const response = await axios.get(
            route('payment.status', { order: props.orderId })
        )

        paymentStatus.value = response.data.status

        if (paymentStatus.value === 'paid' || paymentStatus.value === 'completed') {
            clearInterval(interval)
            interval = null
        }
    } catch (e) {
        console.error(e)
    }
}

onMounted(() => {
    if (paymentStatus.value !== 'paid') {
        checkStatus()
        interval = setInterval(checkStatus, 5000)
    }
})

onUnmounted(() => {
    clearInterval(interval)
})
</script>

<template>
    <section class="wrapper pb-14 pb-md-16">
        <div class="container text-center">

            <!-- STATUS -->
            <div class="row pb-10">
                <div class="col-lg-7 col-xxl-6 mx-auto">
                    <div v-if="paymentStatus === 'created'" class="btn_top">
                        <h3>Twoja płatność jest w trakcie realizacji…</h3>
                        <p>Prosimy nie zamykać strony.</p>
                    </div>

                    <div v-else-if="paymentStatus === 'failed'" class="btn_top">
                        <h3>Płatność nie powiodła się</h3>
                        <p>Spróbuj ponownie.</p>
                    </div>

                    <div v-else-if="paymentStatus === 'paid'" class="btn_top">
                        <h3>Raport planistyczny nr: {{ report.order.reportNumber }}</h3>
                    </div>
                </div>
            </div>

            <!-- RAPORT -->
            <div v-if="paymentStatus === 'paid'" class="col-xl-10 mx-auto">

                <!-- DANE -->
                <div class="box mb-4">
                    <strong>Analizowany adres:</strong><br>
                    {{ report.order.address }}
                </div>

                <div class="box mb-6">
                    <strong>Analizowane współrzędne:</strong><br>
                    {{ report.order.lat }}, {{ report.order.lng }}
                </div>

                <!-- OTOCZENIE -->
                <div class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-2 mb-6">
                    <h2>Otoczenie działki</h2>

                    <p>{{ report.surroundings.developmentDescription }}</p>

                    <ul v-if="report.surroundings.bulletPoints?.length">
                        <li v-for="(point, i) in report.surroundings.bulletPoints" :key="i">
                            {{ point }}
                        </li>
                    </ul>

                    <p><strong>Podsumowanie:</strong> {{ report.surroundings.summary }}</p>
                </div>

                <!-- OGRANICZENIA -->
                <div class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-2 mb-6">
                    <h2>Ograniczenia wynikające z przepisów odrębnych</h2>

                    <template v-if="report.legalConstraints.hasAnyRestrictions">
                        <ul>
                            <li v-for="(point, i) in report.legalConstraints.bulletPoints" :key="i">
                                {{ point }}
                            </li>
                        </ul>

                        <div v-if="report.legalConstraints.legalBasis?.length">
                            <p><strong>Podstawa prawna:</strong></p>
                            <ul>
                                <li v-for="(basis, i) in report.legalConstraints.legalBasis" :key="i">
                                    {{ basis }}
                                </li>
                            </ul>
                        </div>
                    </template>

                    <p v-else>
                        Nie stwierdzono istotnych ograniczeń wynikających z przepisów odrębnych
                        w bezpośrednim otoczeniu działki.
                    </p>

                    <p><strong>Podsumowanie:</strong> {{ report.legalConstraints.summary }}</p>
                </div>

                <!-- FINAL -->
                <div class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-2">
                    <h2>{{ report.finalSummary.headline }}</h2>
                    <p>{{ report.finalSummary.body }}</p>
                    <p><strong>{{ report.finalSummary.callToAction }}</strong></p>
                </div>

            </div>
        </div>
    </section>
</template>
