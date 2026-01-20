<script setup>
import { ref, onMounted, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { loadGoogleMaps } from '@/googleMaps'
import { watch } from 'vue'

let map = null

const addressDirty = ref(false)

defineOptions({ layout: AppLayout })

const mapRef = ref(null)
const markerRef = ref(null)

const markerConfirmed = ref(false)
const needsManualPin = computed(() => !form.ulica || !form.numer)

const form = useForm({
    wojewodztwo: '',
    gmina: '',
    miejscowosc: '',
    ulica: '',
    numer: '',
    address: '',
    lat: null,
    lng: null,
})

/**
 * Składamy adres tekstowy dla Google
 */
const fullAddress = computed(() => {
    return [
        form.ulica && `${form.ulica} ${form.numer}`,
        form.miejscowosc,
        form.gmina,
        form.wojewodztwo,
        'Polska',
    ]
        .filter(Boolean)
        .join(', ')
})

watch(
    () => [
        form.wojewodztwo,
        form.gmina,
        form.miejscowosc,
        form.ulica,
        form.numer,
    ],
    () => {
        if (form.lat && form.lng) {
            addressDirty.value = true
            markerConfirmed.value = false
        }
    }
)

let geocoder

onMounted(async () => {
    try {
        const google = await loadGoogleMaps()

        map = new google.maps.Map(mapRef.value, {
            zoom: 14,
            center: {lat: 52.2297, lng: 21.0122},
        })
    } catch (e) {
        console.error('Google Maps init failed', e)
    }

    markerRef.value = new google.maps.Marker({
        map,
        draggable: true,
    })

    markerRef.value.addListener('dragend', (e) => {
        form.lat = e.latLng.lat()
        form.lng = e.latLng.lng()
    })

    geocoder = new google.maps.Geocoder()
})

/**
 * Geokodujemy po kliknięciu "Dalej"
 * (świadomie NIE robimy autocomplete per pole)
 */
function geocodeAddress() {
    if (!fullAddress.value) return

    geocoder.geocode({ address: fullAddress.value }, (results, status) => {
        if (status !== 'OK' || !results[0]) {
            console.warn('Geocoding failed:', status)
            return
        }

        const location = results[0].geometry.location

        // 🔑 ZAPIS
        form.lat = location.lat()
        form.lng = location.lng()

        // 🔑 MAPA
        map.setCenter(location)
        map.setZoom(16)

        // 🔑 MARKER
        markerRef.value.setPosition(location)
        markerRef.value.setVisible(true)

        markerConfirmed.value = false
        addressDirty.value = false
    })
}

function handleSubmit() {
    if (!form.lat || addressDirty.value) {
        geocodeAddress()
        return
    }

    if (!markerConfirmed.value) {
        return
    }

    form.address = fullAddress.value
    form.post('/analysis/start')
}
</script>

<template>
    <section class="wrapper pb-14 pb-md-16">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="row gy-10 gx-lg-8 gx-xl-12">
                        <div class="col-lg-6 align-self-center">
                            <!-- FORM -->
                            <form class="contact-form needs-validation" @submit.prevent="handleSubmit">
<!--                                <h2 class="text-xl font-semibold mb-6">Sprawdź działkę</h2>-->
                                <div class="row gx-4">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-4">
                                            <input id="form_woje" v-model="form.wojewodztwo" placeholder="Województwo" class="form-control" />
                                            <label for="form_woje">Województwo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-4">
                                            <input id="form_gmina" v-model="form.gmina" placeholder="Gmina" class="form-control" />
                                            <label for="form_gmina">Gmina</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-4">
                                            <input id="form_miejs" v-model="form.miejscowosc" placeholder="Miejscowość" class="form-control" />
                                            <label for="form_miejs">Miejscowość</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-4">
                                            <input id="form_ulic" v-model="form.ulica" placeholder="Ulica" class="form-control" />
                                            <label for="form_ulic">Ulica</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-4">
                                            <input id="form_number" v-model="form.numer" placeholder="Numer porządkowy" class="form-control" />
                                            <label for="form_number">Numer porządkowy</label>
                                        </div>
                                    </div>

                                    <div class="col-md-7 text-center mx-auto mt-4">
                                        <input
                                            type="submit"
                                            class="button_zielony mb-3"
                                            :value="(!form.lat || addressDirty)
    ? 'Pokaż na mapie »'
    : 'Zatwierdź lokalizację »'"
                                            :disabled="!form.lat || addressDirty
    ? false
    : !markerConfirmed"
                                        />
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6 align-self-center">
                            <div>
                                <div
                                    ref="mapRef"
                                    style="width: 100%; height: 450px; background: #eee;"
                                ></div>
                            </div>
                            <p v-if="needsManualPin" class="p2 text-warning mt-3">
                                ⚠️ Nie podano pełnego adresu. Proszę ustawić pinezkę dokładnie na swojej działce.
                            </p>

                            <div class="form-check mt-3">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    v-model="markerConfirmed"
                                    id="confirm_marker"
                                />
                                <label class="form-check-label" for="confirm_marker">
                                    Potwierdzam, że pinezka wskazuje moją działkę
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
