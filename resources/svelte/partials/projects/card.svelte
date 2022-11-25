<script>
    import { onMount } from "svelte";
    import { Inertia } from "@inertiajs/inertia";
    import { modalStore } from "~store/modal-store";
    import { timeAndDateHandling } from "~js/timer";
    import Actions from '~partial/projects/actions';
    import Icon from '~component/icon';

    export let model;

    let activeTab = 'times';
    let elapsedTimeIntervalRef;
    let startedTimer = !! model.active_time;
    let elapsedTimeText = "00:00:00";

    const toggleTimer = () => {
        // Check timer if run before, then stop timer
        if (startedTimer && typeof elapsedTimeIntervalRef !== "undefined") {
            startedTimer = false;
            clearInterval(elapsedTimeIntervalRef);
            elapsedTimeIntervalRef = undefined;

            Inertia.put(route('times.update', model.active_time), {start_at: model.active_time.start_at_formatted, end_at: 'now'}, {preserveScroll: true});
            return;
        }

        let _startAtTime;

        if (!startedTimer) {
            startedTimer = true;
            _startAtTime = new Date();
            elapsedTimeText = "00:00:00";

            Inertia.post(route('times.store', model), {start_at: 'now'}, {preserveScroll: true});
        } else {
            _startAtTime = new Date(model.active_time.start_at_formatted);
            elapsedTimeText = timeAndDateHandling.getElapsedTime(_startAtTime);
        }

        elapsedTimeIntervalRef = setInterval(() => {
            elapsedTimeText = timeAndDateHandling.getElapsedTime(_startAtTime);

            // Todo Improvement: Can Stop elapsed time and reset when a maximum elapsed time
        }, 1000);
    }

    const changeTab = (type) => {
        if (type === 'times' || type === 'wages') {
            activeTab = type;
        }
    }

    const addTimeModal = () => {
        modalStore.create('time', __('Add Time'), route('times.store', model));
    }

    onMount(() => {
        if (startedTimer) {
            toggleTimer();
        }
    });
</script>

<div class="card">
    <div class="card-header">
        <a href="#">
            <h5 class="card-title">{model.name}</h5>
        </a>
        <Actions {model}/>
    </div>
    <div class="card-content">
        <div class="flex justify-center mb-4">
            <div class="btn-group primary">
                <button class="btn-item {activeTab === 'times' ? 'active' : ''}" on:click={() => changeTab('times')}>{__('Times')}</button>
                <button class="btn-item {activeTab === 'wages' ? 'active' : ''}" on:click={() => changeTab('wages')}>{__('Wages')}</button>
            </div>
        </div>
        {#if activeTab === 'times'}
            <div>
                <p class="font-semibold text-center">.:: {__('Time spent on the project')} ::.</p>
                <div class="text-sm text-center opacity-60 space-y-1 mt-2">
                    <p>{__('Times are calculated in hours:minutes.')}</p>
                    <!-- Todo: replace below hint to icon with tooltip -->
                    {#if model.use_persian_datetime_in_statistic && _app.lang === 'fa'}
                        <p>برای محاسبه زمان ها از تاریخ شمسی استفاده شده است.</p>
                    {/if}
                </div>
                <div class="flex justify-between px-16 my-6">
                    <div>
                        <div class="font-semibold">{__('Today')}:</div>
                        <div class="font-semibold">{__('This week')}:</div>
                        <div class="font-semibold">{__('This month')}:</div>
                        <div class="font-semibold">{__('All')}:</div>
                    </div>
                    <div>
                        <div>{model.work_time.today}</div>
                        <div>{model.work_time.week}</div>
                        <div>{model.work_time.month}</div>
                        <div>{model.work_time.all}</div>
                    </div>
                </div>
                <div class="text-center font-semibold transition-all ease-in-out duration-500 {startedTimer ? 'opacity-100' : 'opacity-10'}">
                    <div class="text-lg mb-1">{__('Active Time')}:</div>
                    <div class="text-xl tracking-widest">{elapsedTimeText}</div>
                </div>
            </div>
        {/if}
        {#if activeTab === 'wages'}
            <div>
                <p class="font-semibold text-center">.:: {__('Earnings are based on hourly wages')} ::.</p>
                <div class="flex justify-between px-16 my-6">
                    <div>
                        <div class="font-semibold">{__('Today')}:</div>
                        <div class="font-semibold">{__('This week')}:</div>
                        <div class="font-semibold">{__('This month')}:</div>
                        <div class="font-semibold">{__('All')}:</div>
                    </div>
                    <div>
                        <div>{model.work_wage.today}</div>
                        <div>{model.work_wage.week}</div>
                        <div>{model.work_wage.month}</div>
                        <div>{model.work_wage.all}</div>
                    </div>
                </div>
            </div>
        {/if}
    </div>
    <div class="card-footer">
        <div class="flex justify-center items-center space-x-6">
            <button class="btn-primary" on:click={addTimeModal}>
                <Icon name="add-time"/>
                {__('Add Time')}
            </button>
            <button class={startedTimer ? 'btn-danger' : 'btn-success'} on:click={toggleTimer}>
                <Icon name={startedTimer ? 'timer-off' : 'timer'}/>
                {#if startedTimer}
                    <span>{__('Stop Timer')}</span>
                {:else}
                    <span>{__('Start Timer')}</span>
                {/if}
            </button>
        </div>
    </div>
</div>
