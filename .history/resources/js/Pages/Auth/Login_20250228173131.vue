<script setup>
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import MunkavallaloLogin from '@/Components/MunkavallaloLogin.vue';
import MunkaltatoLogin from '@/Components/MunkaltatoLogin.vue';
import { computed, ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const switchLabel = ref('');

const form = useForm({
    logintype: 'munkavallalo',
});

const computeLabel = computed(() => {
    return form.logintype === 'munkavallalo' ? 'Munkavállaló' : 'Munkáltató';
});

</script>

<template>
    <GuestPageLayout>
        <Head title="Log in" />

        <div class="w-full md:w-1/2 bg-gray-500">
            <v-container class="fill-height">
                <v-row class="justify-center">
                    <v-col cols="12" lg="10" md="10">
                        <v-switch :label="computeLabel" ripple v-model="form.logintype" base-color="#0ea5e9" color="green" false-value="munkavallalo" true-value="munkaltato"></v-switch>
                        <template v-if="form.logintype === 'munkavallalo'">
                                <MunkavallaloLogin></MunkavallaloLogin> 
                        </template>                            
                        <template v-else>
                            <MunkaltatoLogin></MunkaltatoLogin> 
                        </template>
                    </v-col>
                </v-row>
            </v-container>
        </div>

    </GuestPageLayout>
</template>
