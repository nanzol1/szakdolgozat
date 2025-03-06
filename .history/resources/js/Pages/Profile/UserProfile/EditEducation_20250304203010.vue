<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import UpdateProfileMoreInformationForm from './Partials/UpdateProfileMoreInformationForm.vue';
import { ref } from 'vue';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    category: {
        type: Object,
    },
    jobs_interests:{
        type:Array,
    },
    query:{
        type:String,
    },
    institutions:{
        type:Array,
    },
    educations:{
        type:Object,
    }
});

const newEducation = ref(false);
const selectedEducation = ref(null);
const vDialog = ref(false);
const cardRef = ref(null);

const handleSelection = (id) => {
    selectedEducation.value = Object.values(props.educations)?.filter(e => e.id === id);
    vDialog.value = true;
};
</script>

<template>
    <AuthenticatedLayout>
    <Head title="Profil - Képzettségek" />
    <v-container fluid class="bg-white px-0 mx-0">
        <v-container class="!max-w-7xl mx-auto">
            <v-row>
                <v-col>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Profilom / Képzettségek
                    </h2>
                    <h3 class="text-lg font-semibold leading-tight text-gray-800 mt-1">Ezen az oldalon tudja megtekinteni és szerkeszteni profilját.</h3>
                    <div class="flex gap-3 md:gap-5 text-md mt-3">
                        <Link :href="route('profile.edit')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url === '/profile'}]">Alap adataim</Link>
                        <Link :href="route('profile.edit.interests')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url.includes('/erdekeltsegeim')}]">Érdekeltségeim</Link>
                        <Link :href="route('profile.edit.educations')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url.includes('/kepzettsegek')}]">Képzettségek</Link>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </v-container>
    <div class="max-w-7xl mx-auto">
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-btn @click="newEducation = !newEducation" class="hover:bg-sky-500 hover:text-gray-50 mb-3" append-icon="mdi mdi-plus-circle-outline">Új végzettség felvétele</v-btn>
                    <v-expand-transition>
                        <div
                            class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                            v-if="newEducation"
                        >
                            <UpdateProfileMoreInformationForm :educations="educations"/>
                        </div>
                    </v-expand-transition>
                </v-col>
                ss {{ selectedEducation }}
                <v-col cols="12" v-for="educ in educations" :key="educ.id">
                        <v-card
                          class="mx-auto"
                          width="100%"
                          append-icon="mdi mdi-file-edit-outline"
                          @click="handleSelection(educ.id)"
                        >
                          <template v-slot:title>
                            <span class="font-weight-black">{{ educ.institution_name }}</span>
                          </template>
                          <template v-slot:subtitle>
                            <span class="font-weight-black">{{ educ.education_type }}</span>
                          </template>
                      
                          <v-card-text class="bg-surface-light pt-4">
                            {{ educ.education_name }}
                          </v-card-text>
                        </v-card>
                </v-col>
            </v-row>
        </v-container>
        asd
        <div class="text-center pa-4">
    <v-dialog
      v-model="dialog"
      transition="dialog-bottom-transition"
      fullscreen
    >
      <template v-slot:activator="{ props: activatorProps }">
        <v-btn
          prepend-icon="mdi-cog"
          size="small"
          text="Settings"
          v-bind="activatorProps"
        ></v-btn>
      </template>

      <v-card>
        <v-toolbar>
          <v-btn
            icon="mdi-close"
            @click="dialog = false"
          ></v-btn>

          <v-toolbar-title>Settings</v-toolbar-title>

          <v-spacer></v-spacer>

          <v-toolbar-items>
            <v-btn
              text="Save"
              variant="text"
              @click="dialog = false"
            ></v-btn>
          </v-toolbar-items>
        </v-toolbar>

        <v-list lines="two">
          <v-list-subheader>User Controls</v-list-subheader>

          <v-list-item
            subtitle="Set the content filtering level to restrict apps that can be downloaded"
            title="Content filtering"
            link
          ></v-list-item>

          <v-list-item
            subtitle="Require password for purchase or use password to restrict purchase"
            title="Password"
            link
          ></v-list-item>

          <v-divider></v-divider>

          <v-list-subheader>General</v-list-subheader>

          <v-list-item
            subtitle="Notify me about updates to apps or games that I downloaded"
            title="Notifications"
            @click="notifications = !notifications"
          >
            <template v-slot:prepend>
              <v-list-item-action start>
                <v-checkbox-btn v-model="notifications" color="primary"></v-checkbox-btn>
              </v-list-item-action>
            </template>
          </v-list-item>

          <v-list-item
            subtitle="Auto-update apps at any time. Data charges may apply"
            title="Sound"
            @click="sound = !sound"
          >
            <template v-slot:prepend>
              <v-list-item-action start>
                <v-checkbox-btn v-model="sound" color="primary"></v-checkbox-btn>
              </v-list-item-action>
            </template>
          </v-list-item>

          <v-list-item
            subtitle="Automatically add home screen widgets"
            title="Auto-add widgets"
            @click="widgets = !widgets"
          >
            <template v-slot:prepend>
              <v-list-item-action start>
                <v-checkbox-btn v-model="widgets" color="primary"></v-checkbox-btn>
              </v-list-item-action>
            </template>
          </v-list-item>
        </v-list>
      </v-card>
    </v-dialog>
  </div>
    </div>
    </AuthenticatedLayout>
</template>
<style>
    html{
        scroll-behavior: smooth;
    }
</style>
