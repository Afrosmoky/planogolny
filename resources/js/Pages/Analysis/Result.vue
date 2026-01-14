<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const loading = ref(false)

// ⚠️ UWAGA: analysisId musi istnieć
// np. jako prop:
defineProps({
    analysisId: {
        type: [String, Number],
        required: true,
    },
})

function startPayment() {
    if (loading.value) return
    loading.value = true

    router.post(`/payment/start/${analysisId}`)
}
</script>

<template>
    <button
        class="btn-primary w-full"
        :class="{ 'opacity-50 cursor-not-allowed': loading }"
        :disabled="loading"
        @click="startPayment"
    >
        <span v-if="!loading">Generuj raport »</span>
        <span v-else>Przekierowanie do płatności…</span>
    </button>
</template>
