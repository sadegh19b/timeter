<script>
    import { modalStore } from "~store/modal-store";
    import Actions from '~partial/projects/actions';
    import Icon from '~component/icon';

    export let model;

    const addTimeModal = () => {
        modalStore.create('time', __('Time'), route('times.store', model));
    }
</script>

<div class="card">
    <div class="card-header">
        <a href="#">
            <h5 class="card-title">{model.name}</h5>
        </a>
        <Actions {model}/>
    </div>
    <div class="card-content">
        <p class="font-semibold mb-6 text-center">.:: {__('Time spent on the project')} ::.</p>
        <div class="flex justify-between px-16">
            <div>
                <div class="font-semibold">{__('Today')}:</div>
                <div class="font-semibold">{__('This week')}:</div>
                <div class="font-semibold">{__('This month')}:</div>
                <div class="font-semibold">{__('All')}:</div>
            </div>
            <div>
                <div>{model.today_work_time}</div>
                <div>{model.week_work_time}</div>
                <div>{model.month_work_time}</div>
                <div>{model.all_work_time}</div>
            </div>
        </div>
        <p class="text-xs mt-6 text-center opacity-50">{__('Times are calculated in hours:minutes.')}</p>
    </div>
    <div class="card-footer">
        <div class="flex justify-center items-center space-x-6">
            <button class="btn-blue" on:click={addTimeModal}>
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
