<script>
    import PageTitle from '~component/page-title';
    import DarkModeToggle from '~component/dark-mode';
    import Logo from '~/images/logo.png';
</script>

<PageTitle />

<nav class="px-4 py-2.5 bg-brand-primary-35 dark:bg-brand-primary-900">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
        <a href="/" class="flex items-center text-brand-secondary">
            <img class="w-10 h-10 ml-2" src={Logo} alt="{_app.name} Logo">
            <h2 class="text-xl font-semibold mt-2">{__('Timeter')}</h2>
        </a>
        <DarkModeToggle />
    </div>
</nav>
<main class="p-8 mx-auto">
    <slot/>
</main>
<footer>
    {_app.name.toLowerCase()}-v{_app.version}
</footer>
