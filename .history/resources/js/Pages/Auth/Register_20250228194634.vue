<script setup>
import MunkaltatoForm from '@/Components/MunkaltatoForm.vue';
import MunkavallaloForm from '@/Components/MunkavallaloForm.vue';
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineComponent } from 'vue';


const props = defineProps({
    type: String,
});

const components = defineComponent({
    MunkaltatoForm,
    MunkavallaloForm,
});
</script>

<template>
    <Head title="Regisztráció" />

    <GuestPageLayout class="dark:text-white">    
        <v-container>
            <v-row>
                <v-col>
                    <div v-if="type !== 'munkavallalo' && type !== 'munkaltato'">
                    <Link class="block" :href="route('register',{type: 'munkavallalo'})">Munkavállaló</Link>
                    <Link class="block" :href="route('register',{type: 'munkaltato'})">Munkáltató</Link>
                    </div>
                
                    <div v-if="type === 'munkavallalo'">
                        <MunkavallaloForm></MunkavallaloForm>
                    </div>
                    <div v-if="type === 'munkaltato'">
                        <MunkaltatoForm></MunkaltatoForm>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </GuestPageLayout>

</template>
