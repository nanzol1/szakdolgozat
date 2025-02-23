<script setup>
import { useAlertStore } from '@/store';
import { storeToRefs } from 'pinia';
import NavBar from './NavBar.vue';

const alertStore = useAlertStore();
const { alerts } = storeToRefs(alertStore);

</script>
<template>
        <div class="!fixed w-1/3 right-10 bottom-10">
            <transition-group tag="div" name="alert">
                    <v-alert v-for="alert in alerts" :key="alert.id" class="mt-3"
                        :color="alert.type"
                        :icon="'$'+alert.type"
                        :title="alert.message"
                        :text="alert.message"
                        closable
                        density="compact"
                    >
                </v-alert>
            </transition-group>
        </div>
        <NavBar></NavBar>

    <footer class="w-full h-12 bg-black">
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