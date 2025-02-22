<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

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

onMounted(() => {
   window.addEventListener('scroll',handleScroll); 
});

onUnmounted(() => {
   window.removeEventListener('scroll',handleScroll); 
});
</script>
<template>
    <div :class="['navbar',{'fixed-nav':isFixed}]" :style="{transform: isFixed ? 'translateY(0)' : 'translateY(10px)'}">
        <header
        :class="['grid items-center gap-2 static top-0 px-40 lg:w-full max-[991px]:w-62']"
        
    >
        <nav v-if="canLogin" :class="['flex flex-wrap w-full justify-center gap-5 bg-red-500 p-2 rounded-xl xl:text-lg lg:text-md md:text-xs sm:text-sm']">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] 
                dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
                Dashboard
            </Link>
            <template v-else>
                <div class="max-[991px]:hidden">
                    <Link
                    :href="route('/')"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/50 dark:focus-visible:ring-red"
                >
                    Kezdőlap
                </Link>
                <Link
                    :href="route('jobs.show')"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Állások
                </Link>
                <Link
                    :href="route('cv.show')"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Önéletrajz Készítő
                </Link>
                <Link
                    v-if="canRegister"
                    :href="route('register',{type:'munkaltato'})"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Hirdetésfeladás
                </Link>
                <Link
                    :href="route('login')"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Bejelentkezés
                </Link>
                <Link
                    v-if="canRegister"
                    :href="route('register',{type:''})"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Regisztráció
                </Link>
                </div>
            </template>
        </nav>
        <div class="text-center">
    <v-select
      v-model="location"
      :items="locations"
      label="Location"
      theme="dark"
    ></v-select>
    <v-menu :location="location" theme="dark">
      <template v-slot:activator="{ props }">
        <v-btn
          color="primary"
          v-bind="props"
          theme="dark"
        >
          Dropdown
        </v-btn>
      </template>

      <v-list>
        <v-list-item
          v-for="(item, index) in items"
          :key="index"
        >
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
    </header>
    </div>
</template>