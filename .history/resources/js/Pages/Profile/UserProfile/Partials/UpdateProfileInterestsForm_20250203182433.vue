<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import Checkbox from '@/Components/Checkbox.vue';

    const checkboxes = ref([]);

    defineProps({
        asd:Array,
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
        asd {{ asd }}
        <form @prevent.submit="form.post(route('profile.update.interests'))">
            <div>Érdekeltségeim: {{ checkboxes }}</div>
            <label for="adatrogzito">Adatrögzítő</label>
            <Checkbox value="12" v-model:checked="checkboxes" id="adatrogzito"></Checkbox>
            <label for="asd">asd</label>
            <Checkbox value="asd" v-model:checked="checkboxes" id="asd"></Checkbox>
            <label for="34">34</label>
            <Checkbox value="34" v-model:checked="checkboxes" id="34"></Checkbox>
            <label for="dsa">dsa</label>
            <Checkbox value="dsa" v-model:checked="checkboxes" id="dsa"></Checkbox>

        </form>

    </section>
</template>