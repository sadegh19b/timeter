<script>
    import { modalStore } from '~store/modal-store';
    import Modal from '~component/modal/modal-base';
</script>

{#if $modalStore && $modalStore.id === `confirm-${$modalStore.name}`}
    <Modal
        title={$modalStore.title}
        description={$modalStore.description}
        showInfoIcon={$modalStore.showIcon}
    >
        <div class="text-center space-x-2 space-x-reverse">
            <button class="btn-light" on:click={modalStore.closeAction}>{__('Cancel')}</button>
            <button class="btn-blue" on:click={modalStore.confirmAction}>{__("Yes, I'm sure!")}</button>
        </div>
    </Modal>
{/if}
