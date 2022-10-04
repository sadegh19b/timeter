<script>
    import { useForm } from '@inertiajs/inertia-svelte';
    import { format, numberFormat } from '~js/format';
    import { modalStore } from '~store/modal-store';
    import ModalForm from '~component/modal/modal-form';

    export let type; // create or update

    let form = useForm({
        name: (type === 'update')
            ? $modalStore.model.name
            : null,
        pay_per_hour: (type === 'update')
            ? $modalStore.model.pay_per_hour
            : null,
        description: (type === 'update')
            ? $modalStore.model.description
            : null,
    });
</script>

<ModalForm {type} {form}>
    <div class="form-group" class:invalid={$form.errors.name}>
        <label for="name" class="form-label">{__('Project Name')}</label>
        <input id="name" class="form-input" type="text" bind:value={$form.name}/>
        {#if $form.errors.name}
            <div class="form-error">{$form.errors.name}</div>
        {/if}
    </div>
    <div class="form-group" class:invalid={$form.errors.pay_per_hour}>
        <label for="pay_per_hour" class="form-label">{__('Pay Per Hour')} ({__('Optional')})</label>
        <input id="pay_per_hour" class="form-input" use:format={numberFormat} bind:value={$form.pay_per_hour}/>
        {#if $form.errors.pay_per_hour}
            <div class="form-error">{$form.errors.pay_per_hour}</div>
        {/if}
    </div>
    <div class="form-group" class:invalid={$form.errors.description}>
        <label for="description" class="form-label">{__('Project Description')} ({__('Optional')})</label>
        <textarea id="description" class="form-input" bind:value={$form.description} rows="4"></textarea>
        {#if $form.errors.description}
            <div class="form-error">{$form.errors.description}</div>
        {/if}
    </div>
</ModalForm>
