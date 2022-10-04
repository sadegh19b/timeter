<script>
    import Actions from '~partial/projects/actions';
    import Icon from '~component/icon';

    export let model;
</script>

<div class="card">
    <div class="card-header">
        <a href="#">
            <h5 class="card-title">{model.name}</h5>
        </a>
        <Actions {model}/>
    </div>
    <div class="card-content">
        <p>{__('Project Description')}</p>
    </div>
    <div class="card-footer">
        <div class="flex justify-center items-center space-x-6">
            <button class="btn-blue">
                <Icon name="add-time"/>
                {__('Add Time')}
            </button>
            <button class="btn-green">
                <Icon name="timer"/>
                {__('Start Timer')}
            </button>
        </div>
    </div>
</div>
