<script>
    import { modalStore } from '~store/modal-store';
    import Dropdown from '~component/dropdown';
    import Icon from '~component/icon';

    export let model;

    const editProjectModal = () => {
        modalStore.update('project', __('Edit') + ' ' + __('Project'), route('projects.update', model), model);
    }

    const archivingProjectModal = () => {
        modalStore.confirm(
            'project',
            __('Are you sure you want to archiving this project ?'),
            __('After archived the project, you can restore that from archives page.'),
            route('projects.destroy', model),
            'delete'
        );
    }

    const deleteProjectModal = () => {
        modalStore.delete('project', __('Project'), route('projects.destroy_permanent', model));
    }
</script>

<Dropdown position="{_app.rtl ? 'right' : 'left'}">
    <Icon name="more-vert" slot="btn"/>
    <button class="dropdown-item">
        <Icon name="view"/>
        <span>{__('Project Details')}</span>
    </button>
    <button class="dropdown-item" on:click={editProjectModal}>
        <Icon name="edit"/>
        <span>{__('Edit Project')}</span>
    </button>
    <button class="dropdown-item" on:click={archivingProjectModal}>
        <Icon name="send-to-archive"/>
        <span>{__('Archiving the project')}</span>
    </button>
    <button class="dropdown-item" on:click={deleteProjectModal}>
        <Icon name="delete"/>
        <span>{__('Delete Project')}</span>
    </button>
</Dropdown>
