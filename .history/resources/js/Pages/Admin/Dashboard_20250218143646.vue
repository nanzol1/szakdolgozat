<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
const props = defineProps({
    users:{
        type:Array,
    },
    companies:{
        type:Array,
    },
    jobs:{
        type:Array,
    },
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
const registeredCompanies = computed(() => {
    let index = 0;
    props.companies?.companies.forEach(element => {
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
                    Regisztrált munkavállalók száma: {{ props.users.count }}
                </div>
                <div>
                    Ebben a hónapban regisztrált munkavállalók száma: {{ registeredUsers }}
                </div>
                <div>
                    Regisztrált munkáltatók száma: {{ props.companies.count }}
                </div>
                <div>
                    Ebben a hónapban regisztrált munkkáltatók száma: {{ registeredCompanies }}
                </div>
            </div>
            <div class="mt-3 dark:text-white">
                <div>
                    Meghirdetett munkák száma: {{ props.jobs.count }}
                </div>
            </div>
        </section>
    </AdminLayout>
</template>