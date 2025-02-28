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

        <div class="w-full md:w-1/2">
            <v-container class="fill-height">
                <v-row>
                    <v-col cols="12">
                        <div class="w-fit mx-auto">
                            <div>
                            <v-field-label>computeLabel</v-field-label>
                            <v-switch :label="computeLabel" v-model="form.logintype" base-color="black" false-value="munkavallalo" true-value="munkaltato"></v-switch>
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
