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
        <div v-for="alert in alerts" :key="alert.id">
            <v-alert 
                :color="alert.type"
                :icon="'$'+alert.type"
                :title="alert.message"
                text="Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus..."
                closable
                density="compact"
            ></v-alert>
        </div>
    </v-expand-x-transition>
    <footer class="w-full h-12 bg-black">
        FOOTER
    </footer>
</template>