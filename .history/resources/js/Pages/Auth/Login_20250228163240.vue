<script setup>
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import MunkavallaloLogin from '@/Components/MunkavallaloLogin.vue';
import MunkaltatoLogin from '@/Components/MunkaltatoLogin.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    logintype: 'munkavallalo',
});

</script>

<template>
    <GuestPageLayout>
        <Head title="Log in" />

        <div class="w-full md:w-1/2 m-auto">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <div class="flex flex-col justify-center">
                            <div>
                            <label for="munkavallalo">Munkavállaló</label>
                            <input type="radio" v-model="form.logintype" value="munkavallalo" id="munkavallalo">
                        
                            <label for="munkaltato">Munkáltató</label>
                            <input type="radio" v-model="form.logintype" value="munkaltato" id="munkaltato">
                            </div>
                        
                            <div v-if="form.logintype === 'munkavallalo'">
                                <MunkavallaloLogin></MunkavallaloLogin> 
                            </div>
                            <div v-if="form.logintype === 'munkaltato'">
                                <MunkaltatoLogin></MunkaltatoLogin> 
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </div>

    </GuestPageLayout>
</template>
