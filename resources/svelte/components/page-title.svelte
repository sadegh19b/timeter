<script>
    export let title = null;
</script>

<svelte:head>
    <title>{title ? `${title} | ${__('Timeter')}` : __('Timeter')}</title>
</svelte:head>
