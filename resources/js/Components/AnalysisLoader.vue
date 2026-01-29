<template>
    <div v-if="visible" class="analysis-loader">
        <div class="spinner"></div>
        <p class="text">{{ currentText }}</p>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const visible = ref(false)

const texts = [
    'Analizujemy otoczenie działki…',
    'Sprawdzamy plan ogólny…',
    'Analizujemy uwarunkowania przestrzenne…',
    'Oceniamy potencjalne przeznaczenie terenu…'
]

const currentText = ref(texts[0])
let interval = null
let index = 0

function start() {
    visible.value = true
    index = 0
    currentText.value = texts[0]

    interval = setInterval(() => {
        index++
        currentText.value = texts[index % texts.length]
    }, 2500)
}

function stop() {
    visible.value = false
    clearInterval(interval)
}

defineExpose({ start, stop })
</script>

<style scoped>
.analysis-loader {
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.92);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.spinner {
    width: 48px;
    height: 48px;
    border: 3px solid #e5e7eb;
    border-top-color: #2563eb;
    border-radius: 50%;
    animation: spin 0.9s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.text {
    margin-top: 18px;
    font-size: 15px;
    color: #374151;
}
</style>
