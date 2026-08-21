import '@inertiajs/core'

declare module '@inertiajs/core' {
    interface PageProps {
        flash?: {
            success?: string

        },
        user: {
            id: number
            name: string
            email: string
        }
    }
}