<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { ref, watch, computed, onMounted } from 'vue';
    import Checkbox from '@/Components/Checkbox.vue';

    const checkboxes = ref([]);

    const props = defineProps({
        category:{
            type:Object,
        },
        jobs_interests:{
            type:Array,
        }
    });
    const form = useForm({
        interests: []
    });
    onMounted(() => {
        if(props.jobs_interests){
            checkboxes.value = [...props.jobs_interests];
        }
    });

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
const getSubCategories = (cats) => {
    return cats.flatMap(a => [{id:a.subcat_id,name:a.subcat_name}]);
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
        <v-form @submit.prevent="submitForm">
            <transition-group tag="div" name="categories" mode="out-in">
                <div v-for="cats in category" :key="cats.mid"  class="mb-5">
                    <label :for="cats.mid">{{ cats.name }}</label>
                    <Checkbox v-model:checked="checkboxes" :value="cats.mid" :id="cats.mid"></Checkbox>
                    <transition-group tag="div" name="checkboxes">
                        <div v-for="subcat in getSubCategories(cats.subcategories)" :key="subcat.id" class="mt-3" v-if="category.includes(cats.mid)">
                            <label :for="'s'+subcat.id">{{ subcat.name }}</label>
                            <Checkbox v-model:checked="subcategory" :value="subcat.id" :id="'s'+subcat.id"></Checkbox>
                        </div>
                    </transition-group>
                </div>
            </transition-group>
            <div>
                <button type="submit">Küldés</button>
            </div>

        </v-form>

    </section>
</template>