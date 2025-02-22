<script setup>
import { useAlertStore } from '@/store';
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

const alertStore = useAlertStore();


watch(visible, (newVisible) => {
  console.log('Alert látható:', newVisible);
});
</script>
<template>
    <div>

    </div>
    <footer class="w-full h-12 bg-black">
        FOOTER
    </footer>
</template>