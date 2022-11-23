<script>
    import { useForm } from '@inertiajs/inertia-svelte';
    import { format, numberFormat } from '~js/format';
    import { modalStore } from '~store/modal-store';
    import ModalForm from '~component/modal/modal-form';
    import Toggle from '~component/toggle';

    export let type; // create or update

    let form = useForm({
        name: (type === 'update')
            ? $modalStore.model.name
            : null,
        hourly_wage: (type === 'update')
            ? $modalStore.model.hourly_wage
            : null,
        use_persian_datetime_in_statistic: (type === 'update')
            ? $modalStore.model.use_persian_datetime_in_statistic
            : false,
        description: (type === 'update')
            ? $modalStore.model.description
            : null
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
    <div class="form-group" class:invalid={$form.errors.hourly_wage}>
        <label for="hourly_wage" class="form-label">{__('Hourly Wage')} ({__('Optional')})</label>
        <input id="hourly_wage" class="form-input" use:format={numberFormat} bind:value={$form.hourly_wage}/>
        {#if $form.errors.hourly_wage}
            <div class="form-error">{$form.errors.hourly_wage}</div>
        {/if}
    </div>
    <div class="form-group" class:invalid={$form.errors.description}>
        <label for="description" class="form-label">{__('Project Description')} ({__('Optional')})</label>
        <textarea id="description" class="form-input" bind:value={$form.description} rows="4"></textarea>
        {#if $form.errors.description}
            <div class="form-error">{$form.errors.description}</div>
        {/if}
    </div>
    {#if _app.lang === 'fa'}
        <div class="form-group" class:invalid={$form.errors.use_persian_datetime_in_statistic}>
            <Toggle type="primary" bind:value={$form.use_persian_datetime_in_statistic} label="برای محسابه زمان های صرف شده در پروژه از تاریخ شمسی استفاده شود."/>
            {#if $form.errors.use_persian_datetime_in_statistic}
                <div class="form-error">{$form.errors.use_persian_datetime_in_statistic}</div>
            {/if}
        </div>
    {/if}
</ModalForm>
