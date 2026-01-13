<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import { onMounted, onUnmounted, computed } from 'vue'

defineOptions({ layout: AppLayout })

defineProps({
    errors: Object,
    auth: Object,
    analysisId: Number,
    status: String,
})

// 🔹 REAKTYWNE PROPSY Z INERTIA
const analysisId = computed(() => usePage().props.analysisId)
const status = computed(() => usePage().props.status)

// debug (możesz potem usunąć)
console.log('analysisId:', analysisId.value)
console.log('status:', status.value)

let interval = null

onMounted(() => {
    interval = setInterval(() => {
        router.reload({
            only: ['status'],
            preserveScroll: true,
            preserveState: true,
        })
    }, 3000)
})

onUnmounted(() => clearInterval(interval))
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

                        <div v-else-if="status === 'not_found'" class="btn_top">
                            <h3>
                                <!-- <img
                                  src="./assets/img/check.svg"
                                  class="check_ico"
                                /> -->&nbsp;&nbsp;&nbsp;Wroc i popraw dane
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
    <section class="wrapper pb-14 pb-md-16">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="scroll-50 border p-4 bg-white mb-6">
                        <h2 class="mt-2">Regulamin</h2>

                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Vivamus lacinia odio vitae vestibulum vestibulum. Causae
                            interpretaris eam no, clita luptatum viderer eam id.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Vivamus lacinia odio vitae vestibulum vestibulum. Causae
                            interpretaris eam no, clita luptatum viderer eam id.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec
                            vel egestas dolor, ut elementum diam. Aenean elementum, nibh
                            non suscipit interdum, risus felis scelerisque nisi, eu
                            interdum diam diam eu diam.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Vivamus lacinia odio vitae vestibulum vestibulum. Causae
                            interpretaris eam no, clita luptatum viderer eam id.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Vivamus lacinia odio vitae vestibulum vestibulum. Causae
                            interpretaris eam no, clita luptatum viderer eam id.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec
                            vel egestas dolor, ut elementum diam. Aenean elementum, nibh
                            non suscipit interdum, risus felis scelerisque nisi, eu
                            interdum diam diam eu diam.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Vivamus lacinia odio vitae vestibulum vestibulum. Causae
                            interpretaris eam no, clita luptatum viderer eam id.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Vivamus lacinia odio vitae vestibulum vestibulum. Causae
                            interpretaris eam no, clita luptatum viderer eam id.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec
                            vel egestas dolor, ut elementum diam. Aenean elementum, nibh
                            non suscipit interdum, risus felis scelerisque nisi, eu
                            interdum diam diam eu diam.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Vivamus lacinia odio vitae vestibulum vestibulum. Causae
                            interpretaris eam no, clita luptatum viderer eam id.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Vivamus lacinia odio vitae vestibulum vestibulum. Causae
                            interpretaris eam no, clita luptatum viderer eam id.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec
                            vel egestas dolor, ut elementum diam. Aenean elementum, nibh
                            non suscipit interdum, risus felis scelerisque nisi, eu
                            interdum diam diam eu diam.
                        </p>
                    </div>
                    <!--/.row -->
                </div>
                <!-- /column -->

                <div class="col-md-4 text-center mx-auto">
                    <Link
                        :href="route('payment.checkout', { analysis: analysisId })"
                        class="button_zielony mb-3 btn-primary"
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
