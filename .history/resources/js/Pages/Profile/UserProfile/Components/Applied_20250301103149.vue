<script setup>
import { ref } from 'vue';

const props = defineProps({
    isShort:{
        type:Boolean,
    }
});

const isShort = ref(props.isShort || false);
</script>

<template>
    {{ isShort }}
    <div v-if="isShort">
        <div class="dark:text-white mb-3">
        Munkák amire jelentkeztem23
        </div>
        <template v-for="job in myjobs">
            <div class="dark:text-white mb-3">
                {{ job }}
            </div>
        </template>
    </div>
    <div v-else>
        <div class="dark:text-white mb-3">
        Munkák amire jelentkeztemasd
        </div>
        <template v-for="job in myjobs">
            <div class="dark:text-white mb-3">
                {{ job }}
            </div>
        </template>
    </div>
</template>