import '~js/bootstrap';
import '~js/trans';
import '~style/app.scss';

import { createInertiaApp } from '@inertiajs/inertia-svelte';
import { resolvePageComponent } from '~js/resolve-page-component';
import { InertiaProgress } from '@inertiajs/progress';
import Layout from '~component/layout';

createInertiaApp({
    resolve: name => resolvePageComponent(name, import.meta.glob('../svelte/pages/**/*.svelte'), Layout),
    setup({ el, App, props }) {
        new App({ target: el, props });
    },
});

InertiaProgress.init({ includeCSS: false });
