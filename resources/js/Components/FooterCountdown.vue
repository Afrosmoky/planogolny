<template>
    <footer class="pg-footer">
        <div class="container">
            <div class="col-xl-10 mx-auto">
                <div class="row align-items-center gy-3">
                    <!-- COUNTDOWN -->
                    <div class="col-md-6 text-center text-md-start">
                        <div class="countdown-box">
                            <span class="label">Do wejścia w życie planu ogólnego pozostało:</span>
                            <span class="time">
                                {{ days }} dni {{ hours }}h {{ minutes }}m {{ seconds }}s
                            </span>
                        </div>
                    </div>

                    <!-- LINKS -->
                    <div class="col-md-6 text-center text-md-end">
                        <a href="/assets/files/Regulamin_Planogolny_info_PTAHA.docx" target="_blank" class="footer-link">
                            Regulamin
                        </a>
                        <span class="separator">|</span>
                        <a href="assets/files/Polityka_Prywatnosci_Planogolny_info.docx" target="_blank" class="footer-link">
                            Polityka prywatności
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const targetDate = new Date('2026-07-01T00:00:00')

const days = ref(0)
const hours = ref(0)
const minutes = ref(0)
const seconds = ref(0)

let interval: number | undefined

const updateCountdown = () => {
    const now = new Date().getTime()
    const distance = targetDate.getTime() - now

    if (distance <= 0) {
        days.value = hours.value = minutes.value = seconds.value = 0
        return
    }

    days.value = Math.floor(distance / (1000 * 60 * 60 * 24))
    hours.value = Math.floor((distance / (1000 * 60 * 60)) % 24)
    minutes.value = Math.floor((distance / (1000 * 60)) % 60)
    seconds.value = Math.floor((distance / 1000) % 60)
}

onMounted(() => {
    updateCountdown()
    interval = window.setInterval(updateCountdown, 1000)
})

onUnmounted(() => {
    if (interval) clearInterval(interval)
})
</script>

<style scoped>
.pg-footer {
    border-top: 1px solid rgba(255,255,255,0.08);
    background: transparent;
    font-size: 14px;
}

.countdown-box {
    display: inline-flex;
    flex-direction: column;
    gap: 4px;
}

.countdown-box .label {
    opacity: 0.7;
    font-size: 13px;
}

.countdown-box .time {
    font-weight: 600;
    letter-spacing: 0.5px;
}

.footer-link {
    color: inherit;
    text-decoration: none;
    opacity: 0.8;
}

.footer-link:hover {
    opacity: 1;
    text-decoration: underline;
}

.separator {
    margin: 0 10px;
    opacity: 0.4;
}
</style>
