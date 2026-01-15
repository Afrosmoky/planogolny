<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { onMounted, onUnmounted, ref } from 'vue'
import axios from 'axios'

defineOptions({ layout: AppLayout })

const props = defineProps({
    orderId: Number,
    results: Object
})

const status = ref('pending')
let interval = null

const checkStatus = async () => {
    try {
        const response = await axios.get(
            route('payment.status', { order: props.orderId })
        )

        status.value = response.data.status

        if (status.value === 'paid' || status.value === 'completed') {
            clearInterval(interval)
            interval = null
        }
    } catch (e) {
        console.error(e)
    }
}

onMounted(() => {
    //checkStatus()
    //interval = setInterval(checkStatus, 5000)
})

onUnmounted(() => {
    //if (interval) clearInterval(interval)
})

</script>

<template>
    <section class="wrapper pb-14 pb-md-16">

        <div class="container text-center">
            <div class="row pb-10">
                <div
                    class="col-lg-7 col-xxl-6 mx-auto text-center"

                >
                    <div
                        class="d-flex justify-content-center mb-5 mb-md-0"

                    >
                        <div v-if="status === 'created'" class="btn_top">
                            <h3>Twoja płatność jest w trakcie realizacji…</h3>
                            <p>Prosimy nie zamykać strony.</p>
                        </div>

                        <div v-else-if="status === 'paid'" class="btn_top">
                            <h3>
                                RAPORT – Plan Ogólny
                            </h3>
                        </div>

                        <div v-else-if="status === 'failed'" class="btn_top">
                            <h3>Płatność nie powiodła się</h3>
                            <p>Spróbuj ponownie.</p>
                        </div>

                    </div>
                </div>
            </div>
            <div v-if="status === 'paid'" class="col-xl-10 mx-auto">

                <div class="row gy-10 gx-lg-8 gx-xl-12">
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <div class="d-flex gap-6">
                                <div>Zabudowa jednorodzinna: <strong>50%</strong></div>
                            </div>
                            <div class="d-flex gap-6">
                                <div>Wielorodzinna: <strong>20%</strong></div>
                            </div>
                            <div class="d-flex gap-6">
                                <div>Usługowa: <strong>10%</strong></div>
                            </div>
                            <div class="d-flex gap-6">
                                <div>Przemysłowa: <strong>0%</strong></div>
                            </div>
                            <div class="d-flex gap-6">
                                <div>Zielone / rolne: <strong>20%</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
