<script setup>
import { useAlertStore } from '@/store';
import { storeToRefs } from 'pinia';
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Link, usePage } from '@inertiajs/vue3';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';

const alertStore = useAlertStore();
const { alerts } = storeToRefs(alertStore);

const page = usePage();
const navigation = [
  { name: 'Kezdőlap', href: route('/'), current: page.url === '/' ? true : false },
  { name: 'Állások', href: route('jobs.show'), current: page.url === '/munkak' ? true : false },
  { name: 'Önéletrajz Készítés', href: route('cv.show'), current: page.url === '/cvmaker' ? true : false },
  { name: 'Hirdetésfeladás', href: route('register',{type:'munkaltato'}), current: page.url === '/register/munkaltato' ? true : false },
  { name: 'Bejelentkezés', href: route('login'), current: page.url === '/login' ? true : false },
  { name: 'Regisztráció', href: route('register',{type:''}), current: page.url === '/register' ? true : false },
]
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
        Footer
        <Disclosure as="nav" :class="['text-gray-950 flex flex-wrap w-full justify-center gap-5  bg-sky-500 p-2 xl:text-xl lg:text-md md:text-xs sm:text-sm']" v-slot="{ open }">
            <div class="mx-auto w-full px-2 sm:px-6 lg:px-8">
              <div class="relative flex xl:h-12 lg:h-12 h-8 items-center justify-between">
                <div class="absolute inset-y-0 left-0 flex items-center lg:hidden">
                  <!-- Mobile menu button-->
                  <DisclosureButton class="relative inline-flex items-center justify-center rounded-md p-2 focus:ring-2 dark:hover:bg-stone-950 dark:hover:text-gray-50 hover:bg-gray-50 focus:ring-white focus:outline-hidden focus:ring-inset">
                    <span class="absolute -inset-0.5" />
                    <span class="sr-only">Open main menu</span>
                    <Bars3Icon v-if="!open" class="block size-6" aria-hidden="true" />
                    <XMarkIcon v-else class="block size-6" aria-hidden="true" />
                  </DisclosureButton>
                </div>
                <div class="flex w-full items-center justify-center sm:items-stretch sm:justify-start">
                  <div class="hidden lg:ml-3 lg:block">
                    <div class="flex space-x-4">
                      <Link v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'dark:text-black text-gray-50' : 'hover:text-white dark:text-white dark:hover:text-black transition', 'xl:px-3 xl:py-2 text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</Link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </Disclosure>
    </footer>
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