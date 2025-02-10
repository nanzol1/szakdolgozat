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

    const toggleSubcategories = (category) => {
        console.log(category.id);
  if (checkboxes.value.includes(category.id)) {
    console.log(category);
  }
};
    
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
                <Checkbox :value="categories.name" v-model:checked="checkboxes" :id="categories.id" @change="toggleSubcategories(category)">{{ categories.name }}</Checkbox>
                <div v-for="subcategories in categories.subcategories">
                    {{ subcategories.name }}
                    <Checkbox :value="subcategories.name" v-model:checked="checkboxes" :id="subcategories.id">{{ subcategories.name }}</Checkbox>
                </div>
            </div>

        </form>

    </section>
</template>