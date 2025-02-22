<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const isFixed = ref(false);

const handleScroll = () => {
    if(window.scrollY > 10){
        isFixed.value = true;
    }else{
        isFixed.value = false;
    }
};

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    }
});
const page = usePage();
const navigation = [
  { name: 'Kezdőlap', href: route('/'), current: page.url === '/' ? true : false },
  { name: 'Állások', href: route('jobs.show'), current: page.url === '/munkak' ? true : false },
  { name: 'Önéletrajz Készítés', href: route('cv.show'), current: page.url === '/cvmaker' ? true : false },
  { name: 'Hirdetésfeladás', href: route('register',{type:'munkaltato'}), current: page.url === '/register/munkaltato' ? true : false },
  { name: 'Bejelentkezés', href: route('login'), current: page.url === '/login' ? true : false },
  { name: 'Regisztráció', href: route('register',{type:''}), current: page.url === '/register' ? true : false },
]

onMounted(() => {
   window.addEventListener('scroll',handleScroll); 
});

onUnmounted(() => {
   window.removeEventListener('scroll',handleScroll); 
});
</script>
<template>
    <div :class="['navbar w-full',{'fixed-nav':isFixed}]" :style="{transform: isFixed ? 'translateY(0)' : 'translateY(0)'}">
        <header
        :class="['grid items-center gap-2 top-0']"
        
    >
        <Disclosure as="nav" v-if="canLogin" :class="['text-black flex flex-wrap w-full justify-center gap-5 bg-red-500 p-2 xl:text-lg lg:text-md md:text-xs sm:text-sm',{'rounded-xl': isFixed}]" v-slot="{ open }">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] 
                dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
                Dashboard
            </Link>
            <template v-else>
                
                    <div class="mx-auto w-full px-2 sm:px-6 lg:px-8">
                      <div class="relative flex xl:h-16 lg:h-12 h-8 items-center justify-between">
                        <div class="absolute inset-y-0 left-0 flex items-center lg:hidden">
                          <!-- Mobile menu button-->
                          <DisclosureButton class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:ring-2 focus:ring-white focus:outline-hidden focus:ring-inset">
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
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                      
                          <!-- Profile dropdown -->
                          <Menu as="div" class="relative ml-3">
                            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                              <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 ring-1 shadow-lg ring-black/5 focus:outline-hidden">
                                <MenuItem v-slot="{ active }">
                                  <a href="#" :class="[active ? 'bg-gray-100 outline-hidden' : '', 'block px-4 py-2 text-sm text-gray-700']">Your Profile</a>
                                </MenuItem>
                                <MenuItem v-slot="{ active }">
                                  <a href="#" :class="[active ? 'bg-gray-100 outline-hidden' : '', 'block px-4 py-2 text-sm text-gray-700']">Settings</a>
                                </MenuItem>
                                <MenuItem v-slot="{ active }">
                                  <a href="#" :class="[active ? 'bg-gray-100 outline-hidden' : '', 'block px-4 py-2 text-sm text-gray-700']">Sign out</a>
                                </MenuItem>
                              </MenuItems>
                            </transition>
                          </Menu>
                        </div>
                      </div>
                    </div>
                
                    <DisclosurePanel class="lg:hidden">
                      <div class="space-y-1 px-2 pt-2 pb-3">
                        <Link v-for="item in navigation" :key="item.name" as="a" :href="item.href" :class="[item.current ? 'text-white' : 'text-gray-300 dark:hover:bg-gray-50 dark:hover:text-black', 'block rounded-md px-3 py-2 text-base font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</Link>
                      </div>
                    </DisclosurePanel>
            </template>
        </Disclosure>
    </header>
    </div>
</template>