<script setup>
import { useAlertStore } from '@/store';
import { storeToRefs } from 'pinia';
import { Disclosure} from '@headlessui/vue'
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import Cookies from 'js-cookie';

const alertStore = useAlertStore();
const { alerts } = storeToRefs(alertStore);
alertStore.setupFlashWatcher();

const page = usePage();
const navigation = [
  { name: 'Kezdőlap', href: route('/'), current: page.url === '/' ? true : false },
  { name: 'Állások', href: route('jobs.show'), current: page.url === '/munkak' ? true : false },
  { name: 'Önéletrajz Készítés', href: route('cv.show'), current: page.url === '/cvmaker' ? true : false },
  { name: 'Hirdetésfeladás', href: route('register',{type:'munkaltato'}), current: page.url === '/register/munkaltato' ? true : false },
  { name: 'Bejelentkezés', href: route('login'), current: page.url === '/login' ? true : false },
  { name: 'Regisztráció', href: route('register',{type:''}), current: page.url === '/register' ? true : false },
];
const showBanner = ref(!Cookies.get('cookie_consent'));
const acceptCookies = async() => {
    try{
        const response = await axios.post(route('cookie.consent'), { consent: true });
        if(response.data.success){
            Cookies.set('cookie_consent','true',{expires:365,path:'/',sameSite:'Lax'});
            showBanner.value = false;
        }
    }catch(error){
        console.error(error);
    }
};
const declineCookies = async() => {
    try{
        const response = await axios.post(route('cookie.consent'), { consent: false });
        if(response.data.success){
            Cookies.set('cookie_consent','false',{expires:365,path:'/',sameSite:'Lax'});
            showBanner.value = false;
        }
    }catch(error){
        console.error(error);
    }
};
</script>
<template>
        <div class="!fixed w-1/3 right-10 bottom-10">
            <transition-group tag="div" name="alert">
                    <v-alert v-for="alert in alerts" :key="alert.id" class="mt-3"
                        :color="alert.type"
                        :icon="'$'+alert.type"
                        :title="alert.message"
                        :text="alert.message"
                        closable
                        density="compact"
                    >
                </v-alert>
            </transition-group>
        </div>
    <footer>
        <v-container fluid class="pa-0 !border-solid !border-t !border-black bg-[#C7A07A]">
            <v-container>
                <v-row>
                    <v-col>
                        <Disclosure as="nav" :class="['text-gray-950 flex flex-wrap w-full justify-center gap-5 bg-[#C7A07A] p-2 xl:text-xl lg:text-md md:text-xs sm:text-sm']" v-slot="{ open }">
                            <div class="mx-auto w-full px-2 sm:px-6 lg:px-8">
                              <div class="relative flex xl:h-12 lg:h-12 items-center justify-between">
                                <div class="flex w-full items-center justify-start sm:justify-center sm:items-stretch sm:justify-start">
                                    <div class="flex sm:space-x-4 flex-col sm:flex-row space-y-4 sm:space-y-0">
                                      <Link v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'dark:text-black text-gray-50' : 'hover:text-white dark:text-white dark:hover:text-black transition', 'xl:px-3 xl:py-2 text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</Link>
                                    </div>
                                </div>
                              </div>
                                <div class="text-light text-sm align-center content-center">
                                    {{ new Date().getFullYear() }} - Szakdolgozat: Nánási Zoltán - DWI5GQ
                                </div>
                            </div>
                        </Disclosure>
                    </v-col>
                </v-row>
            </v-container>
        </v-container>
    </footer>
    <div class="!fixed w-full right-0 bottom-0 pa-6 bg-gray-300" v-if="showBanner">
        <transition-group tag="div" name="alert">
            <div class="cookie-banner">
                <p>Az oldalon sütiket használunk a felhasználói élmény javítása érdekében! <Link :href="route('cookie.show')" class="text-stone-950 hover:text-sky-500 text-md font-black">Részletek</Link></p>
                <div class="flex flex-row gap-2 mt-3">
                    <div><v-btn class="hover:bg-green-500 hover:text-gray-50" @click="acceptCookies">Elfogadom</v-btn></div>
                    <div><v-btn class="hover:bg-red-500 hover:text-gray-50" @click="declineCookies">Elutasítom</v-btn></div>
                </div>
            </div>
        </transition-group>
    </div>
</template>
<style scoped>
.alert-enter-active, .alert-leave-active {
  transition: all 0.5s ease;
}
.alert-enter-from, .alert-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
</style>