<script>
    import { onMount, onDestroy } from 'svelte';
    import { fly, fade } from 'svelte/transition';
    import { modalStore } from '~store/modal-store';

    export let title = null;
    export let description = null;
    export let showInfoIcon = false;

    function handleKeydown(event) {
        // 27 : Esc
        if (event.keyCode === 27 && $modalStore.showDialog) {
            modalStore.closeAction();
        }
    }

    onMount(() => {
        document.querySelector('body').classList.add('overflow-y-hidden');
    });
    onDestroy(() => {
        document.querySelector('body').classList.remove('overflow-y-hidden');
    });
</script>

<svelte:window on:keydown={handleKeydown}/>

{#if $modalStore && $modalStore.showDialog}
    <div class="modal-overlay" in:fade={{ duration: 200 }} out:fade={{ delay: 200, duration: 200 }}></div>
    <div class="modal-container">
        <div class="modal-content" in:fly={{ y: -50, delay: 100, duration: 500 }} out:fly={{ y: -50, duration: 500 }}>
            <button type="button" class="modal-close-btn" on:click={modalStore.closeAction}>
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                <span class="sr-only">{__('Close modal')}</span>
            </button>
            <div class="px-4">
                {#if showInfoIcon}
                    <svg aria-hidden="true" class="mx-auto mb-4 w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                {/if}
                {#if title}
                    <h3 class="modal-title">{title}</h3>
                {/if}
                {#if description}
                    <p class="pt-2 pb-6 text-center">{description}</p>
                {/if}
                <slot />
            </div>
        </div>
    </div>
{/if}
