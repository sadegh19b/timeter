<script>
    import { useForm } from '@inertiajs/inertia-svelte';
    import { format, datetimeFormat } from '~js/format';
    import { modalStore } from '~store/modal-store';
    import ModalForm from '~component/modal/modal-form';
    import Alert from '~component/alert';

    export let type; // create or update

    let form = useForm({
        start_at: (type === 'update')
            ? $modalStore.model.start_at
            : null,
        end_at: (type === 'update')
            ? $modalStore.model.end_at
            : null
    });

    let placeholder = _app.lang === 'fa'
        ? 'مثال: 00:00 01-01-1401'
        : 'ex: 2022-01-01 00:00';
</script>

<ModalForm {type} {form}>
    {#if _app.lang === 'fa'}
        <Alert type="primary" id="date-description" showDismissButton={false}>
            <span>تاریخ و ساعت را میتوانید بصورت شمسی یا میلادی وارد کنید.</span>
            <br>
            <span>مثال: 00:00 01-01-1401 یا 00:00 01-01-2022</span>
        </Alert>
    {/if}
    <div class="form-group relative" class:invalid={$form.errors.start_at}>
        <label for="start_at" class="form-label">{__('Start At')}</label>
        <input dir="ltr" id="start_at" class="form-input" type="text"
               use:format={datetimeFormat}
               bind:value={$form.start_at}
               {placeholder}
        />
        {#if $form.errors.start_at}
            <div class="form-error">{$form.errors.start_at}</div>
        {/if}
    </div>
    <div class="form-group relative" class:invalid={$form.errors.end_at}>
        <!--<div class="flex justify-between items-center mb-1">
            <label for="end_at" class="form-label">{__('End At')}</label>
            <button class="btn-light p-1" on:click={() => showEndAtDatetimePicker = !showEndAtDatetimePicker} title="{__('Select date and time')}">
                <Icon className="!m-0" name="calendar-clock"/>
            </button>
        </div>-->
        <label for="end_at" class="form-label">{__('End At')}</label>
        <input dir="ltr" id="end_at" class="form-input" type="text"
               use:format={datetimeFormat}
               bind:value={$form.end_at}
               {placeholder}
        />
        {#if $form.errors.end_at}
            <div class="form-error">{$form.errors.end_at}</div>
        {/if}
    </div>
</ModalForm>
