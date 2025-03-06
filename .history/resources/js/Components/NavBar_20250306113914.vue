<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline'

const isFixed = ref(false);

const handleScroll = () => {
    if(window.scrollY > 10){
        isFixed.value = true;
    }else{
        isFixed.value = false;
    }
};
const is_company = ref(window.sessionData.is_company);
const page = usePage();

const navigation = [
  { name: 'Kezdőlap', href: route('/'), current: page.url === '/' ? true : false },
  { name: 'Állások', href: route('jobs.show'), current: page.url === '/munkak' ? true : false },
  { name: 'Önéletrajz Készítés', href: route('cv.show'), current: page.url === '/cvmaker' ? true : false },
  { name: 'Hirdetésfeladás', href: route('register',{type:'munkaltato'}), current: page.url === '/hirdetes/feladas' ? true : false },
  { name: 'Bejelentkezés', href: route('login'), current: page.url === '/login' ? true : false,isVisible: page.props.auth.user === null || page.props.auth.company === null ? '' : 'hidden'},
  { name: 'Regisztráció', href: route('register',{type:''}), current: page.url === '/register' || page.url === '/register/munkavallalo' || page.url === '/register/munkaltato' ? true : false , isVisible: page.props.auth.user === null || page.props.auth.company === null ? '' : 'hidden'},
  { name: 'Profilom', href: is_company === 1 ? route('cprofile.edit') : route('profile.edit'), current: page.url === '/profile' ? true : false,isVisible: page.props.auth.user === null || page.props.auth.company === null ? 'hidden' : ''},
]
console.log(page.props.auth.company);
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
        <Disclosure as="nav" :class="['text-gray-950 flex flex-wrap w-full justify-center gap-5  bg-sky-500 p-2 xl:text-xl lg:text-md md:text-xs sm:text-sm',{'rounded-xl': isFixed}]" v-slot="{ open }">
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
                      <Link v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'dark:text-black text-gray-50' : 'hover:text-white dark:text-white dark:hover:text-black transition', item.isVisible , 'xl:px-3 xl:py-2 text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</Link>
                    </div>
                  </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
              
                  <!-- Profile dropdown -->
                  <Menu as="div" class="relative ml-3" v-if="$page.props.auth.user">
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
        
            <DisclosurePanel class="lg:hidden w-full">
              <div class="space-y-1 px-2 pt-2 pb-3">
                <Link v-for="item in navigation" :key="item.name" as="a" :href="item.href" :class="[item.current ? 'dark:text-gray-50 dark:bg-black text-stone-950 bg-gray-50' : 'text-gray-50 hover:bg-gray-50 hover:text-stone-950 dark:text-stone-950 dark:hover:text-gray-50 dark:hover:bg-stone-950 transition', item.isVisible, 'block rounded-md px-3 py-2 text-base font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</Link>
              </div>
            </DisclosurePanel>
        </Disclosure>
    </header>
    </div>
</template>