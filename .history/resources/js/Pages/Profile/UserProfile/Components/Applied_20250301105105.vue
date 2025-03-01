<script setup>
import { ref } from 'vue';

const props = defineProps({
    isShort:{
        type:Boolean,
    },
    myjobs:{
        type:Object,
    }
});

const isShort = ref(props.isShort || false);
</script>

<template>
    <div v-if="isShort">
        <div class="mb-3">
            Munkák amire jelentkeztem (rövid)
        </div>
        <template v-for="job in myjobs" :key="job.id">
            <v-row>
                {{ job }}
                <v-col cols="12">
                    <div>
                        <div>{{ job.job_name }}</div>
                        <div>{{ job.job_status_name }}</div>
                        <div>{{ job.job_active }} <v-icon icon="mdi mdi-circle-medium" :color="job.job_active ? 'success' : 'gray'"></v-icon> </div>
                    </div>
                </v-col>
            </v-row>
        </template>
    </div>
    <div v-else>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <div class="mb-3">
                    Munkák amire jelentkeztem (teljes)
                    </div>
                    <template v-for="job in myjobs" :key="job.id">
                        <div class="mb-3">
                            {{ job }}
                        </div>
                    </template>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>