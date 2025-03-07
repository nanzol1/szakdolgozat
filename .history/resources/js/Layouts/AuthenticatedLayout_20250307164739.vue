<script setup>
import { onMounted, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Footer from '@/Components/Footer.vue';
import { Link, router } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const isCompany = ref(window.sessionData.is_company);

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav
                class="border-b border-gray-100 bg-sky-500"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 align-center justify-between">
                        <div class="flex">
                            <!-- Navigation Links -->
                            <Link
                                :href="route('/')"
                                :class="['text-stone-950 hover:text-gray-50']"
                            >
                                Kezdőlap
                            </Link>
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex align-center" v-if="isCompany === 0"
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
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" v-else>
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
                                    :class="['text-stone-950 hover:text-gray-50',{'!text-gray-50':$page.url.includes('munkaim')},{'!text-gray-50':$page.url.includes('cmunka')}]"
                                >
                                    Munkáim
                                </Link>
                            </div>
                        </div>
                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <v-menu open-on-click>
                                    <template v-slot:activator="{props}">
                                        <v-avatar v-if="isCompany === 0" :image="'/storage/uploads/profile_picture/'+$page.props.auth.user.id+'/'+$page.props.auth.user.profile_photo_path"></v-avatar>
                                        <v-avatar v-if="isCompany === 1" :image="'/storage/uploads/company_profile/'+$page.props.auth.company.id+'/'+$page.props.auth.company.profile_pict"></v-avatar>
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
                                            <v-menu open-on-hover v-if="isCompany === 0">
                                                <template v-slot:activator="{props}">
                                                    <Link :href="route('profile.edit')" v-bind="props" class="hover:text-sky-500">Profilom
                                                        <v-icon icon="mdi mdi-arrow-down-drop-circle" class="ml-1" size="x-small"></v-icon>
                                                    </Link>
                                                </template>
                                                <v-list>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('profile.edit')" class="hover:text-sky-500">Alap adatok</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('profile.edit.interests')" class="hover:text-sky-500">Érdekeltségeim</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('profile.edit.educations')" class="hover:text-sky-500">Képzettségek</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                </v-list>
                                            </v-menu>
                                            <v-menu open-on-hover v-else>
                                                <template v-slot:activator="{props}">
                                                    <Link :href="route('cprofile.edit')" v-bind="props" class="hover:text-sky-500">Profilom
                                                        <v-icon icon="mdi mdi-arrow-down-drop-circle" class="ml-1" size="x-small"></v-icon>
                                                    </Link>
                                                </template>
                                                <v-list>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('cprofile.edit')" class="hover:text-sky-500">Alap adatok</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('cprofile.edit.jobs')" class="hover:text-sky-500">Munkahirdetés</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <Link :href="route('cprofile.myjobs')" class="hover:text-sky-500">Munkáim</Link>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                </v-list>
                                            </v-menu>
                                        </v-list-item-title>
                                      </v-list-item>
                                      <v-list-item>
                                        <v-list-item-title>
                                            <Link href="" @click="isCompany === 0 ? router.post(route('logout')) : router.post(route('clogout'))" class="hover:text-sky-500">Kijelentkezés</Link>
                                        </v-list-item-title>
                                      </v-list-item>
                                    </v-list>
                                </v-menu>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
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

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Kezelőfelület
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800 dark:text-gray-200"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow dark:bg-gray-800"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
        <Footer></Footer>
    </div>
</template>
