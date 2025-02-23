<script setup>
import { useAlertStore } from '@/store';
import { usePage } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { watch } from 'vue';

const alertStore = useAlertStore();
const { alerts } = storeToRefs(alertStore);

</script>
<template>
        <div class="!fixed w-1/3 right-10 bottom-10 h-32" v-for="alert in alerts" :key="alert.id">
        <transition-group tag="div" name="alert">
                <v-alert :key="alert.id"
                    color="alert.type"
                    icon="'$'+alert.type"
                    title="alert.message"
                    text="alert.message"
                    closable
                >
            </v-alert>
    </transition-group>
    </div>
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