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

        <div class="w-full md:w-1/2">
            <v-container class="fill-height">
                <v-row>
                    <v-col cols="12">
                        <v-card
    class="mx-auto"
    max-width="500"
  >
    <v-card-title class="text-h6 font-weight-regular justify-space-between">
      <span>{{ currentTitle }}</span>
      <v-avatar
        color="primary"
        size="24"
        v-text="step"
      ></v-avatar>
    </v-card-title>

    <v-window v-model="step">
      <v-window-item :value="1">
        <v-card-text>
          <v-text-field
            label="Email"
            placeholder="john@google.com"
          ></v-text-field>
          <span class="text-caption text-grey-darken-1">
            This is the email you will use to login to your Vuetify account
          </span>
        </v-card-text>
      </v-window-item>

      <v-window-item :value="2">
        <v-card-text>
          <v-text-field
            label="Password"
            type="password"
          ></v-text-field>
          <v-text-field
            label="Confirm Password"
            type="password"
          ></v-text-field>
          <span class="text-caption text-grey-darken-1">
            Please enter a password for your account
          </span>
        </v-card-text>
      </v-window-item>

      <v-window-item :value="3">
        <div class="pa-4 text-center">
          <v-img
            class="mb-4"
            height="128"
            src="https://cdn.vuetifyjs.com/images/logos/v.svg"
          ></v-img>
          <h3 class="text-h6 font-weight-light mb-2">
            Welcome to Vuetify
          </h3>
          <span class="text-caption text-grey">Thanks for signing up!</span>
        </div>
      </v-window-item>
    </v-window>

    <v-divider></v-divider>

    <v-card-actions>
      <v-btn
        v-if="step > 1"
        variant="text"
        @click="step--"
      >
        Back
      </v-btn>
      <v-spacer></v-spacer>
      <v-btn
        v-if="step < 3"
        color="primary"
        variant="flat"
        @click="step++"
      >
        Next
      </v-btn>
    </v-card-actions>
  </v-card>
                        <div class="w-fit mx-auto">
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
