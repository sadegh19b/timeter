<script>
    import { modalStore } from '~store/modal-store';
    import Modal from '~component/modal/modal-base';
    import Spinner from '~component/spinner';

    export let type; // create or update
    export let form;

    const submit = () => {
        $form.clearErrors();
        if ($modalStore.url !== '' && $modalStore.urlType === 'post') {
            $form.post($modalStore.url, {
                onSuccess: page => closeModal()
            });
        } else if ($modalStore.url !== '' && $modalStore.urlType === 'put') {
            $form.put($modalStore.url, {
                onSuccess: page => closeModal()
            });
        }
    }

    const closeModal = () => {
        modalStore.closeAction();
        $form.reset();
    }
</script>

{#if $modalStore && $modalStore.id === `form-${type}-${$modalStore.name}`}
    <Modal title="{type === 'create' ? __('Create') : __('Edit')} {$modalStore.title}">
        <form class="space-y-6" on:submit|preventDefault={submit}>
            <slot/>
            <button class="btn-primary w-full justify-center" type="submit" disabled={$form.processing}>
                {#if $form.processing}
                    <Spinner/>
                    {__('Processing...')}
                {:else}
                    {__('Save')}
                {/if}
            </button>
        </form>
    </Modal>
{/if}
