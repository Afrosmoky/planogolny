<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

defineProps({
    results: Object
})
</script>

<template>
    <section class="py-16 text-center">
        <h2 class="text-2xl font-semibold mb-6">
            RAPORT – Plan Ogólny
        </h2>

        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-2">
            <div>Zabudowa jednorodzinna: <strong>50%</strong></div>
            <div>Wielorodzinna: <strong>20%</strong></div>
            <div>Usługowa: <strong>10%</strong></div>
            <div>Przemysłowa: <strong>0%</strong></div>
            <div>Zielone / rolne: <strong>20%</strong></div>
        </div>

        <a href="#" class="btn-primary mt-6 inline-block">
            Pobierz PDF
        </a>
    </section>
</template>
