import { createInertiaApp } from '@inertiajs/vue3'
import { createApp, h } from 'vue'

import { initializeTheme } from '@/composables/useAppearance'
import AppLayout from '@/layouts/AppLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'
import SettingsLayout from '@/layouts/settings/Layout.vue'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
    title: (title?: string) => (title ? `${title} - ${appName}` : appName),

    resolve: async (name: string) => {
        const pages = import.meta.glob('./pages/**/*.vue')

        const path = `./pages/${name}.vue`

        if (pages[path]) {
            return (await pages[path]()) as any
        }

        const found = Object.keys(pages).find(p =>
            p.endsWith(`${name}.vue`)
        )

        if (found) {
            return (await pages[found]()) as any
        }

        throw new Error(`Page not found: ${name}`)
    },

    setup({ el, App, props, plugin }: any) {
        const vueApp = createApp({ render: () => h(App, props) })
        vueApp.use(plugin)

        if (typeof window !== 'undefined') {
            vueApp.mount(el as Element)
        }

        return vueApp
    },

    progress: {
        color: '#4B5563',
    },
})

// init theme (client only)
if (typeof window !== 'undefined') {
    initializeTheme()
}