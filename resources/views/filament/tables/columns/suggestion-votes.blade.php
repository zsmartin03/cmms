<div class="flex flex-col items-center justify-center space-y-1 py-2">
    @php
        $user = auth()->user();
        $record = $getRecord();
        $hasVoted = $record->hasUserVoted($user);
        $userVoteType = $record->getUserVoteType($user);
        $votesUp = $record->votes_up;
        $votesDown = $record->votes_down;
        $totalScore = $votesUp - $votesDown;
    @endphp

    <!-- Felfelé szavazás -->
    <button wire:click.stop="vote({{ $record->id }}, 'up')" onclick="event.stopPropagation();"
        class="flex items-center justify-center w-7 h-7 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors
               {{ $userVoteType === 'up' ? 'text-orange-500 bg-orange-50 dark:bg-orange-900/20' : 'text-gray-400 hover:text-orange-500' }}"
        title="Pozitív szavazat">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z"
                clip-rule="evenodd" />
        </svg>
    </button>

    <!-- Score megjelenítése -->
    <div
        class="text-sm font-medium {{ $totalScore > 0 ? 'text-orange-500' : ($totalScore < 0 ? 'text-blue-500' : 'text-gray-500') }} select-none">
        {{ $totalScore > 0 ? '+' . $totalScore : $totalScore }}
    </div>

    <!-- Lefelé szavazás -->
    <button wire:click.stop="vote({{ $record->id }}, 'down')" onclick="event.stopPropagation();"
        class="flex items-center justify-center w-7 h-7 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors
               {{ $userVoteType === 'down' ? 'text-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-400 hover:text-blue-500' }}"
        title="Negatív szavazat">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                clip-rule="evenodd" />
        </svg>
    </button>
</div>
