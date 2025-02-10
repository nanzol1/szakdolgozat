<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import Checkbox from '@/Components/Checkbox.vue';

    const checkboxes = ref([]);
    
</script>
<template>
    <section class="w-full">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Milyen munkák érdekelnek?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Itt tudod frissíteni az érdekeltségeidet.
            </p>
        </header>
        <form @prevent.submit="form.post(route('profile.update.interests'))">
            <div>Érdekeltségeim: {{ checkboxes }}</div>
            <Checkbox value="12" v-model:checked="checkboxes" id="12"></Checkbox>
            <Checkbox value="asd" v-model:checked="checkboxes"></Checkbox>
            <Checkbox value="34" v-model:checked="checkboxes"></Checkbox>
            <Checkbox value="dsa" v-model:checked="checkboxes"></Checkbox>
            <label for="12"></label>

        </form>

    </section>
</template>