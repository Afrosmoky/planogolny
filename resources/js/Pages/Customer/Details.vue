<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const form = useForm({
    email: '',
    company_name: '',
    nip: '',
})
</script>

<template>
    <div class="max-w-xl">
        <h2 class="text-2xl font-bold mb-6">Dane do faktury</h2>

        <!-- tu wkleisz swój HTML -->
        <form @submit.prevent="form.post('/customer/details')">
            <input v-model="form.email" placeholder="Email" class="input" />
            <button class="btn-primary">Zapisz</button>
        </form>
    </div>
</template>
