@extends ('layouts.sidebar.sidebar')

@section ('title', 'Suggest an Event | ServeDavao')

@section ('content')
<div class="max-w-2xl mx-auto space-y-8 animate-fade-in">

    {{-- Header --}}
    <div>
        <a href="{{ route('suggestions.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 font-medium mb-4 transition-colors">
            <i class="bi bi-arrow-left"></i> Back to Suggestions
        </a>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Suggest a Future Event 💡</h1>
        <p class="text-gray-500 mt-1 font-medium">Share your idea and let the community vote on it!</p>
    </div>

    {{-- Success / Error Messages --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-4 flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-4">
            <p class="font-semibold mb-2">Please fix the following:</p>
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form action="{{ route('suggestions.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Pass the event context if coming from dashboard prompt --}}
            @if (request('event_id'))
                <input type="hidden" name="suggested_after_event_id" value="{{ request('event_id') }}">
            @endif

            {{-- Title --}}
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                    Event Title <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="e.g. Coastal Clean-Up Drive, Tree Planting, Blood Donation..."
                    maxlength="255"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                >
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Describe the event idea — what it is, why it matters, who it helps..."
                    maxlength="2000"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all resize-none"
                >{{ old('description') }}</textarea>
                <p class="text-xs text-gray-400 mt-1 text-right"><span id="desc-count">0</span>/2000</p>
            </div>

            {{-- Category & Location Row --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Category --}}
                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                    <select
                        id="category"
                        name="category"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                    >
                        <option value="">Select a category...</option>
                        @foreach (['Environment', 'Education', 'Health', 'Community', 'Sports', 'Arts & Culture', 'Animal Welfare', 'Disaster Relief', 'Other'] as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Location --}}
                <div>
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Preferred Location</label>
                    <input
                        type="text"
                        id="location"
                        name="location"
                        value="{{ old('location') }}"
                        placeholder="e.g. Davao Riverfront, Magsaysay Park..."
                        maxlength="255"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                    >
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('suggestions.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-emerald-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-emerald-700 active:scale-95 transition-all shadow-lg hover:shadow-emerald-200 hover:-translate-y-0.5">
                    <i class="bi bi-lightbulb-fill"></i>
                    Submit Suggestion
                </button>
            </div>
        </form>
    </div>

    {{-- Tip --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl px-5 py-4 flex items-start gap-3 text-sm text-blue-800">
        <i class="bi bi-info-circle-fill text-blue-400 mt-0.5"></i>
        <div>
            <p class="font-semibold">Good ideas get more votes!</p>
            <p class="opacity-80 mt-0.5">You'll automatically get the first upvote on your own suggestion. Share the link with fellow volunteers to gather more support.</p>
        </div>
    </div>
</div>

<script>
    // Character counter for description
    const desc = document.getElementById('description');
    const counter = document.getElementById('desc-count');
    const updateCount = () => counter.textContent = desc.value.length;
    desc.addEventListener('input', updateCount);
    updateCount();
</script>
@endsection

