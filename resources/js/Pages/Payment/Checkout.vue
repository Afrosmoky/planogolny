<script setup>
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

defineOptions({ layout: AppLayout })

// 🔹 REAKTYWNY analysisId Z INERTIA
const analysisId = computed(() => usePage().props.analysisId)

// const props = defineProps({
//     analysisId: {
//         type: Number,
//         required: true,
//     },
// })

const form = useForm({
    invoice_type: 'b2c',

    invoice_data: {
        email: '',
        first_name: '',
        last_name: '',
        company_name: '',
        nip: '',
        address: {
            line: '',
            postal_code: '',
            city: '',
            country: 'PL',
        },
    },
})

const isB2B = computed(() => form.invoice_type === 'b2b')

const submit = async () => {
    if (form.processing) return

    form.processing = true

    try {
        const response = await axios.post(
            route('payment.start', { analysisId: analysisId.value }),
            form.data()
        )

        // 🔥 KLUCZOWE: normalny redirect przeglądarki
        window.location.href = response.data.redirect_url
    } catch (error) {
        form.processing = false

        console.error(error)
        alert('Wystąpił błąd podczas inicjalizacji płatności.')
    }
}

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
                                Pobierz Raport – koszt 4,99zł&nbsp;&nbsp;&nbsp;<img
                                src="/assets/img/download.svg"
                                class="dwnl_ico"
                            />
                            </h3>
                        </div>
                    </div>
                </div>
                <!--/column -->
            </div>
            <div class="row pb-2">
                <div
                    class="col-6 col-lg-3 col-xxl-2 mx-auto text-center"

                >
                    <div
                        class="d-flex justify-content-center mb-5 mb-md-0"

                    >
                        <img src="/assets/img/blik.webp" class="img-fluid" />
                    </div>
                </div>
                <!--/column -->
            </div>
            <div class="row pb-10">
                <div
                    class="col-12 mx-auto text-center"

                >
                    <p class="p3">potwierdź kod blik w twoim banku</p>
                </div>
            </div>
            <div class="row pb-10">
                <div
                    class="col-12 mx-auto text-center"

                >
                    <p class="p3">Aby przejść do płatności proszę podać poniższe dane.</p>
                </div>
            </div>
            <form @submit.prevent="submit">
            <div class="col-xl-10 mx-auto">

                <div class="row gy-10 gx-lg-8 gx-xl-12">
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <p class="small text-muted">
                                Wybierz, czy raport kupujesz prywatnie czy jako firma – na tej podstawie
                                przygotujemy rachunek lub fakturę.
                            </p>
                            <label class="form-label fw-bold">Kupuję jako:</label>
                            <div class="d-flex gap-6">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="form.invoice_type"
                                        id="invoice_b2c"
                                        value="b2c"
                                    />
                                    <label class="form-check-label" for="invoice_b2c">
                                        Osoba fizyczna
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="form.invoice_type"
                                        id="invoice_b2b"
                                        value="b2b"
                                    />
                                    <label class="form-check-label" for="invoice_b2b">
                                        Firma
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                            <div v-show="isB2B" class="row gx-4">
                                <p><strong>Dane firmy</strong></p>
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input
                                            id="form_firma"
                                            type="text"
                                            v-model="form.invoice_data.company_name"
                                            class="form-control"
                                            placeholder=""
                                            :required="isB2B"
                                        />
                                        <label for="form_firma">Nazwa firmy</label>
                                        <div class="valid-feedback">Ok!</div>
                                        <div class="invalid-feedback">
                                            Proszę podać nazwę firmy!
                                        </div>
                                    </div>
                                </div>
                                <!-- /column -->
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input
                                            id="form_nip"
                                            type="text"
                                            v-model="form.invoice_data.nip"
                                            class="form-control"
                                            placeholder=""
                                            :required="isB2B"
                                        />
                                        <label for="form_nip">NIP</label>
                                        <div class="valid-feedback">Ok!</div>
                                        <div class="invalid-feedback">Proszę podać NIP!</div>
                                    </div>
                                </div>
                                <!-- /column -->
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input
                                            id="form_email"
                                            type="email"
                                            v-model="form.invoice_data.email"
                                            class="form-control"
                                            placeholder=""
                                            :required="isB2B"
                                        />
                                        <label for="form_email">Adres e-mail</label>
                                        <div class="valid-feedback">Ok!</div>
                                        <div class="invalid-feedback">
                                            Proszę podać adres e-mail!
                                        </div>
                                    </div>
                                </div>
                                <!-- /column -->
                            </div>

                        <div v-show="!isB2B" class="row gx-4">
                            <div class="col-md-12">
                                <p><strong>Dane osoby fizycznej</strong></p>
                                <div class="form-floating mb-4">
                                    <input
                                        id="form_prywemail"
                                        type="text"
                                        v-model="form.invoice_data.first_name"
                                        class="form-control"
                                        placeholder=""
                                        :required="!isB2B"
                                    />
                                    <label for="form_prywemail">Imię</label>
                                    <div class="valid-feedback">Ok!</div>
                                    <div class="invalid-feedback">
                                        Proszę podać imię!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-4">
                                    <input
                                        id="form_prywemail"
                                        type="text"
                                        v-model="form.invoice_data.last_name"
                                        class="form-control"
                                        placeholder=""
                                        :required="!isB2B"
                                    />
                                    <label for="form_prywemail">Nazwisko</label>
                                    <div class="valid-feedback">Ok!</div>
                                    <div class="invalid-feedback">
                                        Proszę podać nazwisko!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-4">
                                    <input
                                        id="form_prywemail"
                                        type="email"
                                        v-model="form.invoice_data.email"
                                        class="form-control"
                                        placeholder=""
                                        :required="!isB2B"
                                    />
                                    <label for="form_prywemail">Adres e-mail</label>
                                    <div class="valid-feedback">Ok!</div>
                                    <div class="invalid-feedback">
                                        Proszę podać adres e-mail!
                                    </div>
                                </div>
                            </div>
                        </div>
                            <!-- /.row -->
                    </div>
                    <!--/column -->
                </div>
                <div class="row gy-6 mt-6">
                    <div class="col-12">
                        <h4 class="mb-3">Adres do rachunku / faktury</h4>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-4">
                            <input
                                type="text"
                                v-model="form.invoice_data.address.line"
                                class="form-control"
                                placeholder=""
                                required
                            />
                            <label>Ulica i numer</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-floating mb-4">
                            <input
                                type="text"
                                v-model="form.invoice_data.address.postal_code"
                                class="form-control"
                                placeholder=""
                                required
                            />
                            <label>Kod pocztowy</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-floating mb-4">
                            <input
                                type="text"
                                v-model="form.invoice_data.address.city"
                                class="form-control"
                                placeholder=""
                                required
                            />
                            <label>Miasto</label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row pb-10">
                <div class="col-md-4 text-center mx-auto">
<!--                    <Link-->
<!--                        v-if="analysisId"-->
<!--                        :href="route('payment.start', { analysis: analysisId })"-->
<!--                        class="button_zielony mb-3 btn-primary"-->
<!--                    >-->
<!--                        Przejdź do płatności »-->
<!--                    </Link>-->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="button_zielony mb-3 btn-primary"
                    >
                        Przejdź do płatności
                    </button>
                </div>
            </div>
            </form>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
</template>
