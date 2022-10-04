<script>
    import { slide } from 'svelte/transition';
    import { quintOut } from 'svelte/easing';
    import clickOutside from 'svelte-outside-click';

    export let position = 'right'; // left, right
    export let width = 'w-64';
    export let menuClass = '';
    export let btnClass = '';

    let showMenu = false;
</script>

<div class="dropdown" use:clickOutside={() => showMenu = false}>
    <button class={btnClass} on:click={() => showMenu = !showMenu}>
        <slot name="btn" />
    </button>
    {#if showMenu}
        <div class="dropdown-menu {position} {width} {menuClass}" transition:slide={{ duration: 300, easing: quintOut }}>
            <slot />
        </div>
    {/if}
</div>
