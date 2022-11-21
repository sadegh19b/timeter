<script>
    import { modalStore } from '~store/modal-store';
    import Modal from '~component/modal/modal-base';
</script>

{#if $modalStore && $modalStore.id === `delete-${$modalStore.name}`}
    <Modal
        title={__('Are you sure you want to delete this :attribute ?', {'attribute': $modalStore.title.toLowerCase()})}
        description={__('Please note that it cannot be restored after deletion.')}
        showInfoIcon={$modalStore.showIcon}
    >
        <div class="text-center space-x-2 space-x-reverse">
            <button class="btn-light" on:click={modalStore.closeAction}>{__('Cancel')}</button>
            <button class="btn-danger" on:click={modalStore.confirmAction}>{__('Yes, Delete it!')}</button>
        </div>
    </Modal>
{/if}
