@extends ('layouts.sidebar.sidebar')

@section ('title', 'Create Poll | ServeDavao')

@section ('content')
<div class="max-w-2xl mx-auto space-y-8 animate-fade-in">

    <div>
        <a href="{{ route('polls.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 font-medium mb-4 transition-colors">
            <i class="bi bi-arrow-left"></i> Back to Polls
        </a>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Create a Poll ðŸ—³ï¸</h1>
        <p class="text-gray-500 mt-1 font-medium">Give volunteers a question with options â€” let them decide what's next!</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-4">
            <p class="font-semibold mb-2">Please fix the following:</p>
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form action="{{ route('polls.store') }}" method="POST" class="space-y-6" id="poll-form">
            @csrf

            {{-- Question / Title --}}
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                    Poll Question <span class="text-red-500">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                    placeholder="e.g. What event should we organize next month?"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all">
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Context / Description <span class="text-gray-400 font-normal">(optional)</span>
                </label>
                <textarea id="description" name="description" rows="3"
                    placeholder="Add context to help volunteers make an informed choice..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- Poll Options --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Poll Options <span class="text-red-500">*</span>
                    <span class="text-gray-400 font-normal">(min 2, max 8)</span>
                </label>

                <div id="options-container" class="space-y-3">
                    @php $oldOptions = old('options', ['', '']); @endphp
                    @foreach ($oldOptions as $i => $val)
                    <div class="option-row flex items-center gap-3">
                        <div class="flex-shrink-0 w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center justify-center option-number">
                            {{ $i + 1 }}
                        </div>
                        <input type="text" name="options[]" value="{{ $val }}"
                            placeholder="Option {{ $i + 1 }}"
                            {{ $i < 2 ? 'required' : '' }}
                            class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all text-sm">
                        @if ($i >= 2)
                        <button type="button" onclick="removeOption(this)"
                                class="text-gray-300 hover:text-red-400 transition-colors p-1">
                            <i class="bi bi-x-circle-fill text-lg"></i>
                        </button>
                        @else
                        <div class="w-8"></div> {{-- spacer for alignment --}}
                        @endif
                    </div>
                    @endforeach
                </div>

                <button type="button" id="add-option-btn"
                        onclick="addOption()"
                        class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-800 transition-colors px-2 py-1">
                    <i class="bi bi-plus-circle-fill"></i> Add Option
                </button>
            </div>

            {{-- Close Date --}}
            <div>
                <label for="closes_at" class="block text-sm font-semibold text-gray-700 mb-2">
                    Poll Deadline <span class="text-gray-400 font-normal">(optional)</span>
                </label>
                <input type="datetime-local" id="closes_at" name="closes_at" value="{{ old('closes_at') }}"
                    class="px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all">
                <p class="text-xs text-gray-400 mt-1">Leave empty for no deadline.</p>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('polls.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">Cancel</a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-emerald-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-emerald-700 active:scale-95 transition-all shadow-lg hover:shadow-emerald-200 hover:-translate-y-0.5">
                    <i class="bi bi-bar-chart-fill"></i> Publish Poll
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let optionCount = {{ count(old('options', ['', ''])) }};
const maxOptions = 8;

function addOption() {
    if (optionCount >= maxOptions) {
        document.getElementById('add-option-btn').textContent = 'Maximum 8 options';
        return;
    }
    optionCount++;
    const container = document.getElementById('options-container');
    const div = document.createElement('div');
    div.className = 'option-row flex items-center gap-3';
    div.innerHTML = `
        <div class="flex-shrink-0 w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center justify-center option-number">${optionCount}</div>
        <input type="text" name="options[]" placeholder="Option ${optionCount}"
               class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all text-sm">
        <button type="button" onclick="removeOption(this)" class="text-gray-300 hover:text-red-400 transition-colors p-1">
            <i class="bi bi-x-circle-fill text-lg"></i>
        </button>`;
    container.appendChild(div);
    renumberOptions();
}

function removeOption(btn) {
    btn.closest('.option-row').remove();
    optionCount--;
    renumberOptions();
    document.getElementById('add-option-btn').textContent = '';
    document.getElementById('add-option-btn').innerHTML = '<i class="bi bi-plus-circle-fill"></i> Add Option';
}

function renumberOptions() {
    document.querySelectorAll('.option-number').forEach((el, i) => { el.textContent = i + 1; });
}
</script>
@endsection

