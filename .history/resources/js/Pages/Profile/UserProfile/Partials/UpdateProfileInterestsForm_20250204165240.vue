<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { ref, watch } from 'vue';
    import Checkbox from '@/Components/Checkbox.vue';

    const checkboxes = ref([]);


    defineProps({
        category:{
            type:Array,
        },
    });
    const form = useForm({
        interests: []
    });

    watch(checkboxes,(newValue) => {
        form.interests = newValue;
    },{deep:true});

    const toggleSubcategories = (category) => {
        if (checkboxes.value.includes(category.id)) {
            category.subcategories.forEach((subcategory) => {
                if (!checkboxes.value.includes(subcategory.id)) {
                    checkboxes.value.push(subcategory.id);
                }
            });
        } else {
            category.subcategories.forEach((subcategory) => {
                const index = checkboxes.value.indexOf(subcategory.id);
                if (index !== -1) {
                    checkboxes.value.splice(index, 1);
                }
        });
    }
};

const submitForm = () => {
    form.post(route('profile.update.interests'));
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
        <form @submit.prevent="submitForm">
            <div>Érdekeltségeim: {{ checkboxes }}</div>
            <div v-for="categories in category" :key="category.id">
                <label :for="categories.name">{{ categories.name }}</label>
                <Checkbox :value="'m'+categories.id" v-model:checked="checkboxes" :id="categories.name" @change="toggleSubcategories(categories)">{{ categories.name }}</Checkbox>
                <div v-for="subcategories in categories.subcategories">
                    <label :for="subcategories.name">{{ subcategories.name }}</label>
                    <Checkbox :value="'s'+subcategories.id" v-model:checked="checkboxes" :id="subcategories.name">{{ subcategories.name }}</Checkbox>
                </div>
            </div>
            <div>
                <button type="submit">Küldés</button>
            </div>

        </form>

    </section>
</template>