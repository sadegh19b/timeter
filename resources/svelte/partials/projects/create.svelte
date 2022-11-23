<script>
    import { modalStore } from '~store/modal-store';
    import Icon from '~component/icon';

    const createProjectModal = () => {
        modalStore.create('project', __('Create') + ' ' + __('Project'), route('projects.store'));
    }
</script>

<div class="card border-dashed min-h-52">
    <div class="text-center">
        <button class="btn-duotone-green-to-blue" on:click={createProjectModal}>
            {__('New Project')}
            <Icon name="arrow-right-alt" sizeClass="w-8 h-8" className="{_app.rtl ? '!mr-2 !-ml-1 transform rotate-180' : '!ml-2 !-mr-1'}" />
        </button>
    </div>
</div>
