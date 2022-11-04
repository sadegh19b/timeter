<script>
    import { page } from '@inertiajs/inertia-svelte';
    import { modalStore } from '~store/modal-store';
    import DeleteModal from '~component/modal/modal-delete';
    import ConfirmModal from '~component/modal/modal-confirm';
    import ProjectCard from '~partial/projects/card';
    import ProjectCreateCard from '~partial/projects/create';
    import ProjectFormModal from '~partial/projects/form';
    import TimeFormModal from '~partial/times/form';

    $: projects = $page.props.projects;
</script>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-8">
    {#each projects as project (project.id)}
        <ProjectCard model={project}/>
    {/each}
    <ProjectCreateCard/>
</div>

<DeleteModal/>
<ConfirmModal/>

{#if $modalStore && $modalStore.id === 'form-create-project'}
    <ProjectFormModal type="create"/>
{/if}
{#if $modalStore && $modalStore.id === 'form-update-project'}
    <ProjectFormModal type="update"/>
{/if}
{#if $modalStore && $modalStore.id === 'form-create-time'}
    <TimeFormModal type="create"/>
{/if}
