<script setup>
import { ref } from 'vue';


const isShort = ref(false);
</script>

<template>
    <div>
        <div class="dark:text-white mb-3">
        Munkák amire jelentkeztem
        </div>
        <template v-for="job in myjobs">
            <div class="dark:text-white mb-3">
                {{ job }}
            </div>
        </template>
    </div>
</template>