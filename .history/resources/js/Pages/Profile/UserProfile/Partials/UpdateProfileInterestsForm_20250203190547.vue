<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import Checkbox from '@/Components/Checkbox.vue';

    const checkboxes = ref([]);


    defineProps({
        category:{
            type:Array,
        },
    });
    
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
            <div v-for="categories in category">
                {{ categories.name }}
                <div v-for="asd in categories">
                    <div v-for="a in asd">
                        {{ a.name }}
                        <Checkbox :value="asd.name" v-model:checked="checkboxes" :id="asd.name">{{ asd.name }}</Checkbox>
                    </div>
                </div>
            </div>

        </form>

    </section>
</template>