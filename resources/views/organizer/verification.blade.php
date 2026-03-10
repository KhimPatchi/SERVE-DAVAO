@extends ('layouts.sidebar.sidebar')

@section ('content')
<div class="min-h-screen bg-gray-50/60 p-6">
    <div class="max-w-5xl mx-auto">
        <!-- Professional Header -->
        <div class="mb-8">
            <nav class="mb-4 flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition-colors hover:text-gray-700">
                    <i class="bi bi-grid mr-1"></i>Dashboard
                </a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-gray-800 font-medium">Organizer Verification</span>
            </nav>

            <div class="flex items-center gap-4">
                <div class="rounded-2xl bg-blue-600 p-3">
                    <i class="bi bi-shield-check text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Identity Verification</h1>
                    <p class="mt-1 text-gray-600">Complete these steps to unlock event creation and management</p>
                </div>
            </div>
        </div>

        <!-- Session Alerts -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill text-2xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-2xl"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl shadow-sm" role="alert">
                <div class="flex items-center gap-3 mb-3">
                    <i class="bi bi-x-circle-fill text-2xl"></i>
                    <strong class="font-bold text-lg">Please fix the following errors:</strong>
                </div>
                <ul class="list-disc list-inside ml-9 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Enhanced Stepper -->
        <div class="mb-8">
            <div class="flex items-center justify-between relative px-8">
                <!-- Progress Line -->
                <div class="absolute top-6 left-0 right-0 h-1 bg-gray-200 -z-10 mx-8"></div>
                <div class="absolute top-6 left-0 h-1 bg-blue-600 transition-all duration-500 -z-10 mx-8" id="step-progress" style="width: 0%"></div>

                <!-- Step 1 -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-lg transition-all duration-300 step-circle relative z-10" data-step="1">1</div>
                    <span class="mt-3 text-sm font-semibold text-blue-700 step-label">Organization</span>
                </div>
                <!-- Step 2 -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-white border-2 border-gray-300 text-gray-500 flex items-center justify-center font-bold text-lg shadow transition-all duration-300 step-circle relative z-10" data-step="2">2</div>
                    <span class="mt-3 text-sm font-medium text-gray-500 step-label">Documents</span>
                </div>
                <!-- Step 3 -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-white border-2 border-gray-300 text-gray-500 flex items-center justify-center font-bold text-lg shadow transition-all duration-300 step-circle relative z-10" data-step="3">3</div>
                    <span class="mt-3 text-sm font-medium text-gray-500 step-label">Consent</span>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden ring-1 ring-gray-100">
            <!-- Accent top bar -->
            <div class="h-2 bg-blue-600"></div>
            
            <form action="{{ route('organizer.verification.store') }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
                @csrf
                
                <!-- Step 1: Organization Details -->
                <div class="p-8 step-content" id="step-1-content">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="p-2 rounded-xl bg-blue-50">
                            <i class="bi bi-building text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Organization Information</h3>
                            <p class="text-sm text-gray-500">Tell us about your organization</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Organization Name *</label>
                            <input type="text" name="organization_name" required value="{{ old('organization_name') }}"
                                class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm hover:border-gray-300"
                                placeholder="Enter legal organization name">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Organization Type *</label>
                            <select name="organization_type" required
                                class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm hover:border-gray-300">
                                <option value="">Select Type</option>
                                <option value="non_profit" {{ old('organization_type') == 'non_profit' ? 'selected' : '' }}>Non-Profit Organization</option>
                                <option value="school" {{ old('organization_type') == 'school' ? 'selected' : '' }}>Educational Institution</option>
                                <option value="community" {{ old('organization_type') == 'community' ? 'selected' : '' }}>Community Group</option>
                                <option value="business" {{ old('organization_type') == 'business' ? 'selected' : '' }}>Registered Business</option>
                                <option value="individual" {{ old('organization_type') == 'individual' ? 'selected' : '' }}>Individual Organizer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Phone *</label>
                            <input type="tel" name="phone" id="phone_input" required value="{{ old('phone') }}"
                                class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm hover:border-gray-300"
                                placeholder="e.g., 09171234567"
                                maxlength="11"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Office Address *</label>
                            <textarea name="address" required rows="3"
                                class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm hover:border-gray-300"
                                placeholder="Complete street address, city">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Identity Verification -->
                <div class="p-8 step-content hidden" id="step-2-content">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="p-2 rounded-xl bg-blue-50">
                            <i class="bi bi-shield-lock text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Identity Documents</h3>
                            <p class="text-sm text-gray-500">Upload your Valid ID and a selfie</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- ID Number -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">ID Number *</label>
                            <input type="text" name="identification_number" required value="{{ old('identification_number') }}"
                                class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm hover:border-gray-300"
                                placeholder="e.g., 123-456-789">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- ID File -->
                            <div>
                                    <div class="relative group">
                                        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-4 text-center hover:border-blue-400 hover:bg-blue-50/30 transition-all bg-gray-50 cursor-pointer overflow-hidden h-64 flex flex-col items-center justify-center relative" onclick="document.getElementById('id_file').click()">
                                            <!-- Default Content -->
                                            <div id="id-default-content" class="flex flex-col items-center">
                                                <div class="w-12 h-12 mb-2 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class="bi bi-card-image text-xl text-blue-600"></i>
                                                </div>
                                                <p class="text-sm font-semibold text-gray-700">Upload ID</p>
                                                <p class="text-xs text-gray-500 mt-1">JPG/PNG, max 10MB</p>
                                            </div>
                                            <!-- Image Preview -->
                                            <img id="id-preview" class="hidden absolute inset-0 w-full h-full object-contain p-2 rounded-2xl bg-gray-100/50" src="" alt="ID Preview">
                                            <!-- Overlay on Hover (when image is present) -->
                                            <div id="id-overlay" class="hidden absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl">
                                                <span class="text-white text-sm font-semibold"><i class="bi bi-pencil mr-1"></i> Change</span>
                                            </div>
                                        </div>
                                        <input type="file" name="identification_document" id="id_file" required accept="image/*" class="hidden" onchange="previewFile(this, 'id-preview', 'id-default-content', 'id-overlay')">
                                    </div>
                            </div>

                            <!-- Selfie File -->
                            <div>
                                    <div class="relative group">
                                        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-4 text-center hover:border-blue-400 hover:bg-blue-50/30 transition-all bg-gray-50 cursor-pointer overflow-hidden h-64 flex flex-col items-center justify-center relative" onclick="document.getElementById('selfie_file').click()">
                                            <!-- Default Content -->
                                            <div id="selfie-default-content" class="flex flex-col items-center">
                                                <div class="w-12 h-12 mb-2 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class="bi bi-camera text-xl text-blue-600"></i>
                                                </div>
                                                <p class="text-sm font-semibold text-gray-700">Upload Selfie</p>
                                                <p class="text-xs text-gray-500 mt-1">JPG/PNG, max 5MB</p>
                                            </div>
                                            <!-- Image Preview -->
                                            <img id="selfie-preview" class="hidden absolute inset-0 w-full h-full object-contain p-2 rounded-2xl bg-gray-100/50" src="" alt="Selfie Preview">
                                            <!-- Overlay on Hover (when image is present) -->
                                            <div id="selfie-overlay" class="hidden absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl">
                                                <span class="text-white text-sm font-semibold"><i class="bi bi-pencil mr-1"></i> Change</span>
                                            </div>
                                        </div>
                                        <input type="file" name="selfie" id="selfie_file" required accept="image/*" class="hidden" onchange="previewFile(this, 'selfie-preview', 'selfie-default-content', 'selfie-overlay')">
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Consent -->
                <div class="p-8 step-content hidden" id="step-3-content">
                    <div class="text-center mb-8">
                        <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-shield-check text-4xl text-blue-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Security & Privacy</h3>
                        <p class="text-gray-600">Review and confirm your submission</p>
                    </div>

                    <div class="bg-blue-50 rounded-2xl p-6 mb-6 border border-blue-100">
                        <div class="flex items-start gap-4 mb-5">
                            <input type="checkbox" required class="mt-1 w-5 h-5 rounded text-blue-600 focus:ring-blue-500 border-gray-300 cursor-pointer">
                            <div class="text-sm text-gray-700">
                                <span class="font-bold text-gray-900">Privacy Consent</span>
                                <p class="mt-1.5 leading-relaxed">I consent to the collection and automated processing of my identification documents and biometric data (selfie) for identity verification purposes.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <input type="checkbox" required class="mt-1 w-5 h-5 rounded text-blue-600 focus:ring-blue-500 border-gray-300 cursor-pointer">
                            <div class="text-sm text-gray-700">
                                <span class="font-bold text-gray-900">Accuracy Declaration</span>
                                <p class="mt-1.5 leading-relaxed">I declare that all information provided is true and accurate. Fraudulent submissions will result in permanent account suspension.</p>
                            </div>
                        </div>
                    </div>

                    <div id="loadingOverlay" class="hidden text-center py-6 bg-blue-50 rounded-2xl">
                        <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600 mb-4"></div>
                        <p class="text-blue-800 font-semibold">verifying your documents...</p>
                        <p class="text-blue-600 text-sm mt-1">Please don't close this window</p>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    <button type="button" id="prevBtn" class="px-6 py-3 rounded-xl text-gray-600 font-semibold hover:bg-gray-200 transition-all invisible">
                        <i class="bi bi-arrow-left mr-2"></i>Previous
                    </button>
                    <button type="button" id="nextBtn" class="px-8 py-3 rounded-xl bg-blue-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                        Next Step<i class="bi bi-arrow-right ml-2"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="px-8 py-3 rounded-xl bg-blue-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all hidden">
                        <i class="bi bi-shield-check mr-2"></i>Submit for Verification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;

    function updateStepUI() {
        // Update contents
        document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
        document.getElementById(`step-${currentStep}-content`).classList.remove('hidden');

        // Update stepper circles
        document.querySelectorAll('.step-circle').forEach((el, index) => {
            const step = parseInt(el.dataset.step);
            el.classList.remove('bg-blue-600', 'text-white', 'bg-white', 'text-gray-500', 'border-gray-300', 'bg-gray-300');
            
            if (step === currentStep) {
                el.classList.add('bg-blue-600', 'text-white', 'scale-110');
            } else if (step < currentStep) {
                el.classList.add('bg-blue-600', 'text-white');
                el.innerHTML = '<i class="bi bi-check-lg text-xl"></i>';
            } else {
                el.classList.add('bg-white', 'text-gray-400', 'border-2', 'border-gray-300');
                el.textContent = step;
            }
        });

        // Update step labels
        document.querySelectorAll('.step-label').forEach((label, index) => {
            const step = index + 1;
            if (step === currentStep) {
                label.classList.remove('text-gray-500');
                label.classList.add('text-blue-700', 'font-semibold');
            } else if (step < currentStep) {
                label.classList.remove('text-gray-500', 'font-medium');
                label.classList.add('text-blue-600', 'font-semibold');
            } else {
                label.classList.remove('text-blue-600', 'text-blue-700', 'font-semibold');
                label.classList.add('text-gray-500', 'font-medium');
            }
        });

        // Update progress bar
        const progress = ((currentStep - 1) / 2) * 100;
        document.getElementById('step-progress').style.width = `${progress}%`;

        // Update buttons
        document.getElementById('prevBtn').style.visibility = currentStep === 1 ? 'hidden' : 'visible';
        if (currentStep === 3) {
            document.getElementById('nextBtn').classList.add('hidden');
            document.getElementById('submitBtn').classList.remove('hidden');
        } else {
            document.getElementById('nextBtn').classList.remove('hidden');
            document.getElementById('submitBtn').classList.add('hidden');
        }
    }

    document.getElementById('nextBtn').addEventListener('click', () => {
        if (validateStep(currentStep)) {
            currentStep++;
            updateStepUI();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        currentStep--;
        updateStepUI();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    function validateStep(step) {
        const currentContent = document.getElementById(`step-${step}-content`);
        const inputs = currentContent.querySelectorAll('[required]');
        let valid = true;
        
        inputs.forEach(input => {
            if (!input.value || !input.value.trim()) {
                input.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                valid = false;
            } else {
                input.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
            }
        });

        if (!valid) {
            alert('âš ï¸ Please fill out all required fields before proceeding.');
        }
        return valid;
    }

    function previewFile(input, previewId, defaultContentId, overlayId) {
        const file = input.files[0];
        const previewFn = document.getElementById(previewId);
        const defaultContent = document.getElementById(defaultContentId);
        const overlay = document.getElementById(overlayId);

        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewFn.src = e.target.result;
                previewFn.classList.remove('hidden');
                defaultContent.classList.add('hidden');
                if (overlay) overlay.classList.remove('hidden');
            }
            
            reader.readAsDataURL(file);
        }
    }

    document.getElementById('multiStepForm').addEventListener('submit', function() {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').classList.add('opacity-50', 'cursor-not-allowed');
        document.getElementById('loadingOverlay').classList.remove('hidden');
    });
</script>

<style>
    .step-circle {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
</style>
@endsection

