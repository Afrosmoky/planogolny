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
                                Twoja płatność jest w trakcie realizacji
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
