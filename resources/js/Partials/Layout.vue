<template>
    <div>
        <portal-target name="dropdown" slim />
        <div class="flex flex-col">
            <div class="min-h-screen flex flex-col" @click="hideDropdownMenus">
                <div class="md:flex">
                    <div class="bg-indigo-darkest md:flex-no-shrink md:w-56 px-6 py-4 flex items-center justify-between md:justify-center">
                        <inertia-link class="mt-1" :href="route('home.index')">
                            <logo class="fill-white" width="120" height="28" />
                        </inertia-link>
                        <dropdown class="md:hidden" placement="bottom-end">
                            <svg class="fill-white w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
                            <div slot="dropdown" class="mt-2 px-8 py-4 shadow-lg bg-indigo-darker rounded">
                                <main-menu />
                            </div>
                        </dropdown>
                    </div>
                    <div class="bg-white border-b w-full p-4 md:py-0 md:px-12 text-sm md:text-base flex justify-between items-center">
                        <div class="mt-1 mr-4">{{ $page.auth.user.email }}</div>
                        <dropdown class="mt-1" placement="bottom-end">
                            <div class="flex items-center cursor-pointer select-none group">
                                <div class="text-grey-darkest group-hover:text-indigo-dark focus:text-indigo-dark mr-1 whitespace-no-wrap">
                                    <span>{{ $page.auth.user.name }}</span>
                                    <!--<span class="hidden md:inline">Gomez</span>-->
                                </div>
                                <icon class="w-5 h-5 group-hover:fill-indigo-dark fill-grey-darkest focus:fill-indigo-dark" name="cheveron-down" />
                            </div>
                            <div slot="dropdown" class="mt-2 py-2 shadow-lg bg-white rounded text-sm">
                                <inertia-link class="block px-6 py-2 hover:bg-indigo hover:text-white" href="#">Mi perfil</inertia-link>
                                <inertia-link class="block px-6 py-2 hover:bg-indigo hover:text-white" :href="route('logout')" method="post">Cerrar sesión</inertia-link>
                            </div>
                        </dropdown>
                    </div>
                </div>
                <div class="flex flex-grow">
                    <div class="bg-indigo-darker flex-no-shrink w-56 p-12 hidden md:block">
                        <main-menu />
                    </div>
                    <div class="w-full overflow-hidden px-4 py-8 md:p-12">
                        <flash-messages />
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Dropdown from '@/Partials/Dropdown'
    import FlashMessages from '@/Partials/FlashMessages'
    import Icon from '@/Partials/Icon'
    import Logo from '@/Partials/Logo'
    import MainMenu from '@/Partials/MainMenu'

    export default {
        components: {
            Dropdown,
            FlashMessages,
            Icon,
            Logo,
            MainMenu,
        },
        props: {
            title: String,
        },
        data() {
            return {
                showUserMenu: false,
                accounts: null,
            }
        },
        watch: {
            title(title) {
                this.updatePageTitle(title)
            },
        },
        mounted() {
            this.updatePageTitle(this.title)
        },
        methods: {
            updatePageTitle(title) {
                document.title = title ? `${title} | ${this.$page.app.name}` : this.$page.app.name
            },
            hideDropdownMenus() {
                this.showUserMenu = false
            },
        },
    }
</script>