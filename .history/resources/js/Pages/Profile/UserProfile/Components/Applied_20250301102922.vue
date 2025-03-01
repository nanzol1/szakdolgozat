<script setup>
import { ref } from 'vue';


const isShort = ref(false);
</script>

<template>
    <div v-if="isShort">
        <div class="dark:text-white mb-3">
        Munkák amire jelentkeztem
        </div>
        <template v-for="job in myjobs">
            <div class="dark:text-white mb-3">
                {{ job }}
            </div>
        </template>
    </div>
    <div v-else>
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