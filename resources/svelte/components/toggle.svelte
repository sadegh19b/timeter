<script>
    export let type; // primary, danger, success, warning, purple, teal
    export let label;
    export let value; // boolean
    export let disabled = false;
    export let clickableLabel = false;
</script>

<div class="flex items-center">
    <label class="inline-flex relative cursor-pointer">
        <input type="checkbox" class="sr-only peer" bind:checked={value} {value} {disabled}>
        <span class="peer toggle toggle-{type}"></span>
        {#if clickableLabel}
            <div class="toggle-label{disabled ? ' text-gray-400 dark:text-gray-500' : ''}">{label}</div>
        {/if}
    </label>
    {#if !clickableLabel}
        <div class="toggle-label{disabled ? ' text-gray-400 dark:text-gray-500' : ''}">{label}</div>
    {/if}
</div>

