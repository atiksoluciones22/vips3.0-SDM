<div>
    @switch($actionCode)
        @case(\TypeActions::ENTRY->value)
            <livewire:human-capital.actions.entry :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::CHANGE_INDICATOR->value)
            <livewire:human-capital.actions.change-indicator :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::WAGE->value)
            <livewire:human-capital.actions.wage :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::TIP->value)
            <livewire:human-capital.actions.tip :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::License->value)
            <livewire:human-capital.actions.license :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::VACATION->value)
            <livewire:human-capital.actions.vacation :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::TRANSFER->value)
            <livewire:human-capital.actions.transfer :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::JOB_CHANGE->value)
            <livewire:human-capital.actions.job-change :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::SCHEDULE_CHANGE->value)
            <livewire:human-capital.actions.schedule-change :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::GRADUATION->value)
            <livewire:human-capital.actions.graduation :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::REENTRY->value)
            <livewire:human-capital.actions.reentry :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::ABSENCE->value)
            <livewire:human-capital.actions.absence :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::ADMONITION->value)
            <livewire:human-capital.actions.admonition :actionRequest="$actionRequest" />
        @break
        @case(\TypeActions::SUSPENSION->value)
            <livewire:human-capital.actions.suspension :actionRequest="$actionRequest" />
        @break
    @endswitch
</div>
