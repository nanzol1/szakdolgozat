<script setup>
import { useAlertStore } from '@/store';
import { usePage } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { watch } from 'vue';

const alertStore = useAlertStore();
const { alerts } = storeToRefs(alertStore);


</script>
<template>
        <transition-group tag="div" name="alert">
                <v-alert class="!fixed w-1/4 right-0 top-0"
                    color="error"
                    icon="'$'+alert.type"
                    title="alert.message"
                    text="alert.message"
                    closable
                    density="compact"
                ></v-alert>
    </transition-group>
    <footer class="w-full h-12 bg-black">
        FOOTER
    </footer>
</template>
<style scoped>
.alert-enter-active, .alert-leave-active {
  transition: all 0.5s ease;
}
.alert-enter-from, .alert-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
</style>