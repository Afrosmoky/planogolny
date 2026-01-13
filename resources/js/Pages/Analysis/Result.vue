<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const loading = ref(false)

function startPayment() {
    if (loading.value) return
    loading.value = true

    router.post(`/payment/start/${analysisId}`)
}
</script>

<button
    class="btn-primary w-full"
    :class="{ 'opacity-50 cursor-not-allowed': loading }"
    :disabled="loading"
    @click="startPayment"
>
<span v-if="!loading">Generuj raport »</span>
<span v-else>Przekierowanie do płatności…</span>
</button>
