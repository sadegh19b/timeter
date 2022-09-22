/**
 * Imports the given page component from the page record, and assign global layout if not null..
 *
 * @see https://laravel-vite.dev/guide/extra-topics/inertia.html#example-implementation
 */

export async function resolvePageComponent(name, pages, layout = null) {
    for (const path in pages) {
        if (path.endsWith(`${name.split('.').join('/')}.svelte`)) {
            return (typeof pages[path] === 'function')
                ? (layout !== null)
                    ? Object.assign({layout: layout}, await pages[path]())
                    : pages[path]()
                : (layout !== null)
                    ? Object.assign({layout: layout}, await pages[path])
                    : pages[path]
        }
    }

    throw new Error(`Page not found: ${name}`)
}
