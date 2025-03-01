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
        <template v-for="job in myjobs">
            <div class="mb-3">
                {{ job }}
            </div>
        </template>
    </div>
    <div v-else>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <div class="mb-3">
                    Munkák amire jelentkeztem (teljes)
                    </div>
                    <template v-for="job in myjobs">
                        <div class="mb-3">
                            {{ job }}
                        </div>
                    </template>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>