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
    function     toggleSubcategories(category) {
      // Ellenőrizzük, hogy a fő kategória be van-e jelölve
      if (this.checkboxes.includes(category.id)) {
        // Ha be van jelölve, bejelöljük az összes kapcsolódó alkategóriát
        const relatedSubcategories = this.categories
          .flatMap(cat => cat.subcategories) // Minden alkategória összegyűjtése
          .filter(subcategory => subcategory.maincateg_id === category.id); // Csak azok, amelyek a fő kategóriához tartoznak

        relatedSubcategories.forEach(subcategory => {
          if (!this.checkboxes.includes(subcategory.id)) {
            this.checkboxes.push(subcategory.id); // Hozzáadjuk a bejelölt alkategóriát
          }
        });
      } else {
        // Ha a fő kategóriát kikapcsolják, eltávolítjuk a kapcsolódó alkategóriákat
        const relatedSubcategories = this.categories
          .flatMap(cat => cat.subcategories)
          .filter(subcategory => subcategory.maincateg_id === category.id);

        relatedSubcategories.forEach(subcategory => {
          const index = this.checkboxes.indexOf(subcategory.id);
          if (index > -1) {
            this.checkboxes.splice(index, 1); // Eltávolítjuk a nem kívánt alkategóriát
          }
        });
      }
    },
    
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
                <Checkbox :value="categories.name" v-model:checked="checkboxes" :id="categories.name">{{ categories.name }}</Checkbox>
                <div v-for="subcategories in categories.subcategories">
                    {{ subcategories.name }}
                    <Checkbox :value="subcategories.name" v-model:checked="checkboxes" :id="subcategories.name">{{ subcategories.name }}</Checkbox>
                </div>
            </div>

        </form>

    </section>
</template>