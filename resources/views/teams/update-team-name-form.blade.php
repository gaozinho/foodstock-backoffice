<x-jet-form-section submit="updateTeamName">
    <x-slot name="title">
        {{ __('Nome da equipe') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Nome da equipe e informações do responsável.') }}
    </x-slot>

    <x-slot name="form">
        <x-jet-action-message on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <!-- Team Owner Information -->
        <div class="mb-4">
            <x-jet-label value="{{ __('Responsável') }}" />

            <div class="d-flex mt-2">
                <img class="rounded-circle mr-2" width="48" src="{{ $team->owner->profile_photo_url }}">
                <div>
                    <div>{{ $team->owner->name }}</div>
                    <div class="text-muted">{{ $team->owner->email }}</div>
                </div>
            </div>
        </div>

        <!-- Team Name -->
        <div class="w-md-75">
            <div class="form-group">
                <x-jet-label for="name" value="{{ __('Nome da equipe') }}" />

                <x-jet-input id="name"
                             type="text"
                             class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                             wire:model.defer="state.name"
                             :disabled="! Gate::check('update', $team)" />

                <x-jet-input-error for="name" />
            </div>
        </div>
    </x-slot>

    @if (Gate::check('update', $team))
        <x-slot name="actions">
			<div class="d-flex align-items-baseline">
				<x-jet-button>
					{{ __('Salvar') }}
				</x-jet-button>
			</div>
        </x-slot>
    @endif
</x-jet-form-section>