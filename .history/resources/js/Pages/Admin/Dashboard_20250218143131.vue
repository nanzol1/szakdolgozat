<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
const props = defineProps({
    users:{
        type:Array,
    }
});

const registeredUsers = computed(() => {
    let index = 0;
    props.users?.users.forEach(element => {
        if(new Date(element.created_at).toLocaleDateString().split('/')[0] == new Date(Date.now()).toLocaleDateString().split('/')[0] 
        && new Date(element.created_at).toLocaleDateString().split('/')[1] >= 1 
        && new Date(element.created_at).toLocaleDateString().split('/')[1] <= 30){
            index++;
        }
    });
    return index;
});

</script>

<template>
    <Head title="Dashboard"></Head>
    <AdminLayout>
        <section>
            <div class="block w-full dark:text-white">
                <div>
                    Regisztrált felhasználók: {{ props.users.count }}
                </div>
                <div>
                    Ebben a hónapban regisztrált felhasználók száma: {{ registeredUsers }}
                </div>
            </div>
        </section>
    </AdminLayout>
</template>