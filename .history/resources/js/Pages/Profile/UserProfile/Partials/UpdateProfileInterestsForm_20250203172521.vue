<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import Checkbox from '@/Components/Checkbox.vue';

    const checkboxes = ref([]);
    
    console.log(checkboxes);
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
            {{ checkboxes }}
            <Checkbox v-model="checkboxes" id="jack"></Checkbox>
        </form>

    </section>
</template>