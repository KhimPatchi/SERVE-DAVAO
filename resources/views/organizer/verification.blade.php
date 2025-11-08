@extends('layouts.sidebar.sidebar')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
  
  <!-- Header - AVATAR REMOVED -->
  <div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Become a Verified Organizer</h1>
    <div class="text-gray-600">
    </div>
  </div>

  <!-- Success/Error Messages -->
  @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
      <strong class="font-bold">Success!</strong>
      <span class="block sm:inline">{{ session('success') }}</span>
    </div>
  @endif

  @if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
      <strong class="font-bold">Error!</strong>
      <span class="block sm:inline">{{ session('error') }}</span>
    </div>
  @endif

  @if($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
      <strong class="font-bold">Please fix the following errors:</strong>
      <ul class="mt-1 list-disc list-inside">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Verification Form Card -->
  <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-800 mb-2">Organizer Verification</h2>
      <p class="text-gray-600">
        To create and manage events, you need to be verified as an organizer. 
        Please provide the following information for verification.
      </p>
    </div>

   <form method="POST" action="{{ route('organizer.verification.store') }}" enctype="multipart/form-data" id="verificationForm" onsubmit="return validateForm()" autocomplete="off">
      @csrf

      <!-- Organization Information -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
          <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-2">Organization Name *</label>
          <input id="organization_name" 
                 class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 border @error('organization_name') border-red-500 @enderror" 
                 type="text" 
                 name="organization_name" 
                 value="{{ old('organization_name') }}" 
                 required 
                 maxlength="255" />
          @error('organization_name')
            <p class="mt-2 text-sm text-red-600" id="organization_name_error">{{ $message }}</p>
          @enderror
          <p class="mt-1 text-sm text-gray-500" id="organization_name_count">0/255 characters</p>
        </div>

        <div>
          <label for="organization_type" class="block text-sm font-medium text-gray-700 mb-2">Organization Type *</label>
          <select id="organization_type" 
                  name="organization_type" 
                  class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm px-4 py-3 border @error('organization_type') border-red-500 @enderror" 
                  required>
            <option value="">Select Type</option>
            <option value="non_profit" {{ old('organization_type') == 'non_profit' ? 'selected' : '' }}>Non-Profit Organization</option>
            <option value="school" {{ old('organization_type') == 'school' ? 'selected' : '' }}>School/University</option>
            <option value="community" {{ old('organization_type') == 'community' ? 'selected' : '' }}>Community Group</option>
            <option value="business" {{ old('organization_type') == 'business' ? 'selected' : '' }}>Business</option>
            <option value="individual" {{ old('organization_type') == 'individual' ? 'selected' : '' }}>Individual</option>
            <option value="other" {{ old('organization_type') == 'other' ? 'selected' : '' }}>Other</option>
          </select>
          @error('organization_type')
            <p class="mt-2 text-sm text-red-600" id="organization_type_error">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <!-- Identification -->
      <div class="mb-6">
        <label for="identification_number" class="block text-sm font-medium text-gray-700 mb-2">Government ID Number *</label>
        <input id="identification_number" 
               class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 border @error('identification_number') border-red-500 @enderror" 
               type="text" 
               name="identification_number" 
               value="{{ old('identification_number') }}" 
               required 
               maxlength="50" 
               pattern="[A-Za-z0-9-]+" 
               title="Only letters, numbers, and hyphens are allowed" />
        @error('identification_number')
          <p class="mt-2 text-sm text-red-600" id="identification_number_error">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-sm text-gray-500">Format: Letters, numbers, and hyphens only</p>
      </div>

      <!-- Identification Document Upload -->
      <div class="mb-6">
        <label for="identification_document" class="block text-sm font-medium text-gray-700 mb-2">
          Upload ID Document (JPG, PNG, PDF - Max 2MB) *
        </label>
        
        <!-- Custom File Upload -->
        <div class="relative">
          <input id="identification_document" 
                 class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 @error('identification_document') border-red-500 @enderror" 
                 type="file" 
                 name="identification_document" 
                 accept=".jpg,.jpeg,.png,.pdf" 
                 required 
                 onchange="validateFile(this)" />
          
          <!-- Custom styled file input -->
          <div class="flex items-center justify-between border border-gray-300 rounded-lg shadow-sm px-4 py-3 bg-white hover:border-teal-500 transition cursor-pointer @error('identification_document') border-red-500 @enderror" id="file-upload-area">
            <span class="text-gray-600 truncate mr-2" id="file-name">No file chosen</span>
            <span class="bg-teal-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-teal-700 transition whitespace-nowrap">
              Choose File
            </span>
          </div>
        </div>
        
        <!-- Selected file info -->
        <p class="mt-2 text-sm text-gray-500" id="selected-file-info"></p>
        <p class="mt-1 text-sm text-gray-500">Accepted formats: JPG, JPEG, PNG, PDF | Max size: 2MB</p>
        
        @error('identification_document')
          <p class="mt-2 text-sm text-red-600" id="identification_document_error">{{ $message }}</p>
        @enderror
      </div>

      <!-- Contact Information -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
          <input id="phone" 
                 class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 border @error('phone') border-red-500 @enderror" 
                 type="tel" 
                 name="phone" 
                 value="{{ old('phone') }}" 
                 required 
                 pattern="[0-9+\-\s()]+" 
                 title="Please enter a valid phone number" />
          @error('phone')
            <p class="mt-2 text-sm text-red-600" id="phone_error">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
          <textarea id="address" 
                    name="address" 
                    rows="3" 
                    class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm px-4 py-3 border @error('address') border-red-500 @enderror" 
                    required 
                    maxlength="500">{{ old('address') }}</textarea>
          @error('address')
            <p class="mt-2 text-sm text-red-600" id="address_error">{{ $message }}</p>
          @enderror
          <p class="mt-1 text-sm text-gray-500" id="address_count">0/500 characters</p>
        </div>
      </div>

      <div class="flex items-center justify-end mt-8">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg disabled:bg-gray-400 disabled:cursor-not-allowed" id="submitBtn">
          Submit for Verification
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// Character counters
document.getElementById('organization_name').addEventListener('input', function() {
  const count = this.value.length;
  document.getElementById('organization_name_count').textContent = `${count}/255 characters`;
});

document.getElementById('address').addEventListener('input', function() {
  const count = this.value.length;
  document.getElementById('address_count').textContent = `${count}/500 characters`;
});

// File validation
function validateFile(input) {
  const file = input.files[0];
  const fileInfo = document.getElementById('selected-file-info');
  const fileNameDisplay = document.getElementById('file-name');
  const fileUploadArea = document.getElementById('file-upload-area');
  const errorElement = document.getElementById('identification_document_error');
  
  // Reset styles
  fileUploadArea.classList.remove('border-red-500', 'border-green-500');
  
  if (file) {
    const fileSize = (file.size / 1024 / 1024).toFixed(2);
    const fileExtension = file.name.split('.').pop().toLowerCase();
    const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    const maxSize = 2; // 2MB
    
    // Validate file type
    if (!allowedExtensions.includes(fileExtension)) {
      fileInfo.textContent = `Error: Please select JPG, PNG, or PDF file only`;
      fileInfo.className = 'mt-2 text-sm text-red-600';
      fileUploadArea.classList.add('border-red-500');
      input.value = ''; // Clear the file input
      fileNameDisplay.textContent = 'No file chosen';
      
      if (errorElement) {
        errorElement.textContent = 'Please select a valid file type (JPG, PNG, PDF)';
      }
      return false;
    }
    
    // Validate file size
    if (fileSize > maxSize) {
      fileInfo.textContent = `Error: File size (${fileSize} MB) exceeds 2MB limit`;
      fileInfo.className = 'mt-2 text-sm text-red-600';
      fileUploadArea.classList.add('border-red-500');
      input.value = ''; // Clear the file input
      fileNameDisplay.textContent = 'No file chosen';
      
      if (errorElement) {
        errorElement.textContent = 'File size must be less than 2MB';
      }
      return false;
    }
    
    // Valid file
    fileInfo.textContent = `Selected: ${file.name} (${fileSize} MB)`;
    fileInfo.className = 'mt-2 text-sm text-green-600';
    fileUploadArea.classList.add('border-green-500');
    fileNameDisplay.textContent = file.name;
    
    // Clear any existing errors
    if (errorElement) {
      errorElement.textContent = '';
    }
    
    return true;
  } else {
    fileInfo.textContent = '';
    fileNameDisplay.textContent = 'No file chosen';
    return false;
  }
}

// Form validation
function validateForm() {
  const form = document.getElementById('verificationForm');
  const submitBtn = document.getElementById('submitBtn');
  let isValid = true;
  
  // Basic required field validation
  const requiredFields = form.querySelectorAll('[required]');
  requiredFields.forEach(field => {
    if (!field.value.trim()) {
      isValid = false;
      field.classList.add('border-red-500');
    } else {
      field.classList.remove('border-red-500');
    }
  });
  
  // File validation
  const fileInput = document.getElementById('identification_document');
  if (fileInput.files.length === 0) {
    isValid = false;
    document.getElementById('file-upload-area').classList.add('border-red-500');
  }
  
  if (!isValid) {
    alert('Please fill in all required fields correctly.');
    return false;
  }
  
  // Disable submit button to prevent double submission
  submitBtn.disabled = true;
  submitBtn.textContent = 'Submitting...';
  
  return true;
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
  const fields = document.querySelectorAll('input, select, textarea');
  fields.forEach(field => {
    field.addEventListener('blur', function() {
      if (this.hasAttribute('required') && !this.value.trim()) {
        this.classList.add('border-red-500');
      } else {
        this.classList.remove('border-red-500');
      }
    });
    
    field.addEventListener('input', function() {
      this.classList.remove('border-red-500');
    });
  });
});
</script>
@endsection