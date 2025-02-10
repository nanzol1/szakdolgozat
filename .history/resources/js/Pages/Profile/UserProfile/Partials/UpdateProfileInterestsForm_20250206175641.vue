<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { ref, watch, computed, onMounted } from 'vue';
    import Checkbox from '@/Components/Checkbox.vue';

    const checkboxes = ref([]);
    const user = usePage().props.user.auth;

    const props = defineProps({
        category:{
            type:Array,
        },
        jobs_interests:{
            type:Array,
        }
    });
    const form = useForm({
        interests: []
    });
    onMounted(() => {
        checkboxes.value = [...props.jobs_interests];
    });
console.log([props.jobs_interests]);

    const selectedNames = computed(() => {
        return checkboxes.value.map((id) => {
            for (const category of Object.values(props.category) || []){
                if('m' + category.id === id){
                    return category.name;
                }
                for (const subcategory of category.subcategories || []){
                    if('s' + subcategory.id === id){
                        return subcategory.name;
                    }
                }
            }
            return null;
        }).filter(Boolean);
    });

    watch(checkboxes,(newValue) => {
        form.interests = newValue;
    },{deep:true});

    const toggleSubcategories = (category) => {
        if (checkboxes.value.includes('m'+category.id)) {
            category.subcategories.forEach((subcategory) => {
                if (!checkboxes.value.includes('s'+subcategory.id)) {
                    checkboxes.value.push('s'+subcategory.id);
                }
            });
        } else {
            category.subcategories.forEach((subcategory) => {
                const index = checkboxes.value.indexOf('s'+subcategory.id);
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
        {{ checkboxes }}
        <form @submit.prevent="submitForm">
            <div>Érdekeltségeim: <div v-for="interests in selectedNames">{{ interests }}</div></div>
            <div v-for="categories in category" :key="categories.id">
                <label :for="categories.id">{{ categories.name }}</label>
                <Checkbox :value="'m'+categories.id" v-model:checked="checkboxes" :id="categories.id" @change="toggleSubcategories(categories)">{{ categories.name }}</Checkbox>
                <div v-for="subcategories in categories.subcategories" :key="subcategories.id">
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