<script setup>
import { useAlertStore } from '@/store';
import { usePage } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { watch } from 'vue';

const alertStore = useAlertStore();
const { alerts } = storeToRefs(alertStore);


</script>
<template>
    <v-expand-x-transition>
        <transition-group tag="div">
            <div v-for="alert in alerts" :key="alert.id">
                <v-alert
                    :color="alert.type"
                    :icon="'$'+alert.type"
                    :title="alert.message"
                    :text="alert.message"
                    closable
                    density="compact"
                ></v-alert>
            </div>
    </transition-group>
    </v-expand-x-transition>
    <footer class="w-full h-12 bg-black">
        FOOTER
    </footer>
</template>