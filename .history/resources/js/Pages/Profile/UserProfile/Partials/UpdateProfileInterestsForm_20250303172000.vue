<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { ref, watch, onMounted } from 'vue';
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
    watch(checkboxes,(newValue) => {
        form.interests = newValue;
    },{deep:true});

    const submitForm = () => {
        form.post(route('profile.update.interests'));
    };
    const getSubCategories = (cats) => {
        console.log(cats);
        return cats.flatMap(a => [{id:a.id,name:a.name}]);
    };   
</script>
<template>
    <v-container>
        <v-row>
            <header class="mb-2 mb-md-5">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Milyen munkák érdekelnek?
                </h2>
            
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Itt tudod frissíteni az érdekeltségeidet.
                </p>
            </header>
            <v-col cols="12">
                <section class="w-full">
                    <v-form @submit.prevent="submitForm">
                        <transition-group tag="div" name="categories" mode="out-in">
                            <div v-for="cats in category" :key="cats.id"  class="mb-5">
                                <v-checkbox :label="cats.name" v-model="checkboxes" :value="'m'+cats.id" :id="cats.id"></v-checkbox>
                                <transition-group tag="div" name="checkboxes">
                                    <div v-for="subcat in getSubCategories(cats.subcategories)" :key="subcat.id" class="mt-3" v-if="checkboxes.includes('m'+cats.id)">
                                        <v-checkbox :label="subcat.name" v-model="checkboxes" :value="'s'+subcat.id" :id="'s'+subcat.id"></v-checkbox>
                                    </div>
                                </transition-group>
                            </div>
                        </transition-group>
                        <div>
                            <v-btn type="submit" :disabled="form.processing" class="hover:bg-sky-500 hover:text-white">{{form.recentlySuccessful ? 'Mentve' : 'Mentés'}}</v-btn>
                        </div>
                    
                    </v-form>
                
                </section>
            </v-col>
        </v-row>
    </v-container>
</template>
<style scoped>
.checkboxes-enter-active, .checkboxes-leave-active {
  transition: all 0.5s ease;
}
.checkboxes-enter-from, .checkboxes-leave-to {
  opacity: 0;
}
</style>