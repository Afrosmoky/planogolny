<template>

            <header class="wrapper pb-13">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-10 mx-auto">
                            <div
                                class="row justify-content-center justify-content-md-between"
                            >
                                <div class="col-11 col-md-5 mt-7 align-self-center">
                                    <img src="/assets/img/top_l.svg" class="img-fluid" />
                                </div>
                                <div class="col-11 col-md-5 mt-7 align-self-center">
                                    <img src="/assets/img/top_r.svg" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>


            <slot />
            <!-- PAGE CONTENT -->

            <!--- Footer goes here-->
            <FooterCountdown />

</template>
<script setup lang="ts">
import FooterCountdown from "@/Components/FooterCountdown.vue";
</script>
