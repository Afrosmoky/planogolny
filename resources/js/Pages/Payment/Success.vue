<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

defineProps({
    results: Object
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
                        <div class="btn_top">
                            <h3>
                                RAPORT – Plan Ogólny
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-10 mx-auto">

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
