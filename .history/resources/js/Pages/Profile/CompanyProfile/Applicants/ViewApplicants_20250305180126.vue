<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout>
        <Head></Head>
        <section class="bg-white p-4 shadow sm:rounded-lg sm:p-8 sm:py-2 dark:bg-gray-800 flex justify-between flex-col">
            Jelentkező(k)
            <div v-for="apps in applications">
                {{ apps }}
                <button @click="showPopUp(apps.user_id)">Megtekintem</button>
            </div>
            <Transition>
                <div v-if="isPopup" class="popup">
                    <button @click="closePopup()">X</button>
                    <div>
                        {{ selectedApplicant.name }}
                    </div>
                    <div>
                        {{ selectedApplicant.email }}
                    </div>
                    <div>
                        {{ selectedApplicant.phone }}
                    </div>
                    <template v-for="(sts, index) in statuses" :key="sts.id">
                        <div v-if="sts.name !== 'Jelentkezett'">
                            <label :for="sts.id">{{ sts.name }}</label>
                            <input type="radio" v-model="status" :id="sts.name" :name="sts.name" :value="sts.id" :disabled="index < status.valueOf()">
                        </div>
                    </template>
                </div>
            </Transition>
        </section>
    </AuthenticatedLay>
</template>