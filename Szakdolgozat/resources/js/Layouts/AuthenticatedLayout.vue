<script setup>
import { ref } from 'vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Footer from '@/Components/Footer.vue';
import { Link, router, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const page = usePage();
const isCompany = ref(page.props.auth?.is_company || 0);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav
                class="border-b !border-black bg-[#C7A07A]"
            >
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 align-center !text-md justify-between">
                        <div class="flex">
                            
                            <Link
                                :href="route('/')"
                                :class="['text-stone-950 hover:text-gray-50']"
                            >
                                Főoldal
                            </Link>
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 md:flex align-center" v-if="isCompany === 0"
                            >
                                <Link
                                    :href="route('dashboard')"
                                    :class="['text-stone-950 hover:text-gray-50',{'!text-gray-50':$page.url === '/dashboard'}]"
                                >
                                    Kezelőfelület
                                </Link>
                                <Link
                                    :href="route('profile.edit')"
                                    :class="['text-stone-950 hover:text-gray-50',{'!text-gray-50':$page.url.includes('profile')}]"
                                >
                                    Profilom
                                </Link>
                                <Link
                                    :href="route('profile.myjobs')"
                                    :class="['text-stone-950 hover:text-gray-50',{'!text-gray-50':$page.url.includes('munkaim')}]"
                                >
                                    Jelentkezéseim
                                </Link>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 md:flex" v-else>
                                <Link
                                    :href="route('cdashboard')"
                                    :class="['text-stone-950 hover:text-gray-50',{'!text-gray-50':$page.url === '/cdashboard'}]"
                                >
                                    Kezelőfelület
                                </Link>
                                <Link
                                    :href="route('cprofile.edit')"
                                    :class="['text-stone-950 hover:text-gray-50',{'!text-gray-50':$page.url.includes('/company-profile')}]"
                                >
                                    Profilom
                                </Link>
                                <Link
                                    :href="route('cprofile.edit.jobs')"
                                    :class="['text-stone-950 hover:text-gray-50',{'!text-gray-50':$page.url.includes('/hirdetes')}]"
                                >
                                    Munkahirdetés
                                </Link>
                                <Link
                                    :href="route('cprofile.myjobs')"
                                    :class="['text-stone-950 hover:text-gray-50',{'!text-gray-50':$page.url.includes('munkaim')},{'!text-gray-50':$page.url.includes('cmunkaim')}]"
                                >
                                    Munkáim
                                </Link>
                            </div>
                        </div>
                        <div class="hidden sm:ms-6 md:flex sm:items-center">
                            <div class="relative ms-3">
                                <v-menu open-on-click>
                                    <template v-slot:activator="{props}">
                                        <v-avatar v-if="isCompany === 0 && $page.props.auth.user.profile_photo_path" :image="'/storage/uploads/profile_picture/'+$page.props.auth.user.id+'/'+$page.props.auth.user.profile_photo_path"></v-avatar>
                                        <v-avatar v-if="isCompany === 1 && $page.props.auth.company.profile_pict" :image="'/storage/uploads/company_profile/'+$page.props.auth.company.id+'/'+$page.props.auth.company.profile_pict"></v-avatar>
                                        <v-btn
                                          v-bind="props"
                                          variant="text"
                                          class="hover:text-gray-50"
                                        >
                                          {{ $page.props.auth.user.name ?? $page.props.auth.company.name }}
                                        <v-icon icon="mdi mdi-arrow-down-drop-circle" class="ml-1"></v-icon>
                                        </v-btn>
                                    </template>
                                    <v-list>
                                      <v-list-item>
                                        <v-list-item-title>
                                            <v-menu v-if="isCompany === 0" submenu>
                                                <template v-slot:activator="{props}">
                                                    <v-btn v-bind="props" class="hover:text-[#a67c52] pa-0" elevation="0">Profilom
                                                        <v-icon icon="mdi mdi-arrow-right-drop-circle" class="ml-1" size="x-small"></v-icon>
                                                    </v-btn>
                                                </template>
                                                <v-list>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('profile.edit')" class="hover:text-[#a67c52]">Alap adatok</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('profile.edit.interests')" class="hover:text-[#a67c52]">Érdekeltségeim</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('profile.edit.educations')" class="hover:text-[#a67c52]">Képzettségek</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('profile.edit.cv')" class="hover:text-[#a67c52]">Önéletrajz</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                </v-list>
                                            </v-menu>
                                            <v-menu v-else submenu>
                                                <template v-slot:activator="{props}">
                                                    <v-btn v-bind="props" class="hover:text-[#a67c52] pa-0" elevation="0">Profilom
                                                        <v-icon icon="mdi mdi-arrow-right-drop-circle" class="ml-1" size="x-small"></v-icon>
                                                    </v-btn>
                                                </template>
                                                <v-list>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('cprofile.edit')" class="hover:text-[#a67c52]">Alap adatok</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('cprofile.edit.jobs')" class="hover:text-[#a67c52]">Munkahirdetés</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('cprofile.myjobs')" class="hover:text-[#a67c52]">Munkáim</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                </v-list>
                                            </v-menu>
                                        </v-list-item-title>
                                      </v-list-item>
                                      <v-list-item>
                                        <v-list-item-title>
                                            <Link href="" @click="isCompany === 0 ? router.post(route('logout')) : router.post(route('clogout'))" class="hover:text-[#a67c52]">Kijelentkezés</Link>
                                        </v-list-item-title>
                                      </v-list-item>
                                    </v-list>
                                </v-menu>
                            </div>
                        </div>

                        <div class="-me-2 flex items-center md:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="#000"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="md:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2" v-if="isCompany === 0">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                            class="text-stone-950 hover:text-gray-50"
                        >
                            Kezelőfelület
                        </ResponsiveNavLink>
                    </div>
                    <div class="space-y-1 pb-3 pt-2" v-else>
                        <ResponsiveNavLink
                            :href="route('cdashboard')"
                            :active="route().current('cdashboard')"
                            class="text-stone-950 hover:text-gray-50"
                        >
                            Kezelőfelület
                        </ResponsiveNavLink>
                    </div>

                    <div
                        class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600"
                    >
                        <div class="px-4" v-if="isCompany === 0">
                            <div
                                class="mb-2"
                            >
                                <v-avatar v-if="isCompany === 0 && $page.props.auth.user.profile_photo_path" :image="'/storage/uploads/profile_picture/'+$page.props.auth.user.id+'/'+$page.props.auth.user.profile_photo_path"></v-avatar>
                            </div>
                            <div
                                class="text-base font-medium text-gray-800"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-stone-950">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>
                        <div class="px-4" v-else>
                            <div
                                class="mb-2"
                            >
                                <v-avatar v-if="isCompany === 1 && $page.props.auth.company.profile_pict" :image="'/storage/uploads/company_profile/'+$page.props.auth.company.id+'/'+$page.props.auth.company.profile_pict"></v-avatar>
                            </div>
                            <div
                                class="text-base font-medium text-stone-950"
                            >
                                {{ $page.props.auth.company.name }}
                            </div>
                            <div class="text-sm font-medium text-stone-950">
                                {{ $page.props.auth.company.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')" class="border-b border-black text-stone-950 hover:text-gray-50">
                                Profilom
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-stone-950 hover:text-gray-50"
                                v-if="isCompany === 0"
                            >
                                Kijelentkezés
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('clogout')"
                                method="post"
                                as="button"
                                class="text-stone-950 hover:text-gray-50"
                                v-else
                            >
                                Kijelentkezés
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <header
                class="gradient-bg shadow dark:bg-gray-800"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
        <Footer></Footer>
    </div>
</template>
