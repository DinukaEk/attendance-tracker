@if($errors->any())
    <div class="px-4 py-3 mb-4 text-sm text-red-600 border border-red-200 rounded-lg bg-red-50">
        <ul class="space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="p-6 space-y-5 bg-white border shadow-sm border-stone-200 rounded-xl">
    <div>
        <label class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">
            Name <span class="text-red-400">*</span>
        </label>
        <input type="text" name="name" value="{{ old('name', $student->name ?? '') }}" required
               class="w-full px-3 py-2 text-sm border rounded-lg bg-stone-50 border-stone-200 text-stone-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
    </div>
    <div>
        <label class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">
            Registration Number <span class="text-red-400">*</span>
        </label>
        <input type="text" name="registration_number" value="{{ old('registration_number', $student->registration_number ?? '') }}" required
               class="w-full px-3 py-2 text-sm border rounded-lg bg-stone-50 border-stone-200 text-stone-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
    </div>
    <div>
        <label class="block mb-3 text-xs font-medium tracking-wide uppercase text-stone-500">Enroll in Subjects</label>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
            @foreach($subjects as $subject)
                @php $checked = in_array($subject->id, old('subjects', $enrolledIds ?? [])); @endphp
                <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors
                              {{ $checked ? 'bg-indigo-50 border-indigo-300' : 'bg-stone-50 border-stone-200 hover:border-stone-300' }}">
                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                           class="w-4 h-4 accent-indigo-600" {{ $checked ? 'checked' : '' }}
                           onchange="this.closest('label').className = this.checked
                               ? 'flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors bg-indigo-50 border-indigo-300'
                               : 'flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors bg-stone-50 border-stone-200 hover:border-stone-300'">
                    <div>
                        <div class="text-sm font-medium text-stone-800">{{ $subject->name }}</div>
                        <div class="font-mono text-xs text-stone-400">{{ $subject->code }}</div>
                    </div>
                </label>
            @endforeach
        </div>
        @if(isset($isEdit) && !auth()->user()->isAdmin())
            <p class="mt-2 text-xs text-stone-400">You can only manage enrolment for your own subjects.</p>
        @endif
    </div>
</div>