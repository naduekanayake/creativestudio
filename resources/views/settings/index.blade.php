@extends('layouts.app')

@section('title', 'Settings')

@section('content')

{{-- Cropper.js CDN --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<div class="max-w-4xl mx-auto" x-data="{ tab: 'studio' }">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Settings</h1>
        <p class="text-gray-400 text-sm mt-0.5">Manage your studio information and preferences</p>
    </div>

    @if(session('success'))
    <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4 text-sm">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-2 mb-4">
        <button @click="tab = 'studio'"
                class="text-sm font-medium px-4 py-2 rounded-lg transition-colors"
                :class="tab === 'studio' ? 'bg-primary text-white' : (dark ? 'bg-dark-700 text-gray-400 hover:text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200')">
            Studio Info
        </button>
        <button @click="tab = 'bank'"
                class="text-sm font-medium px-4 py-2 rounded-lg transition-colors"
                :class="tab === 'bank' ? 'bg-primary text-white' : (dark ? 'bg-dark-700 text-gray-400 hover:text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200')">
            Bank Details
        </button>
        <button @click="tab = 'invoice'"
                class="text-sm font-medium px-4 py-2 rounded-lg transition-colors"
                :class="tab === 'invoice' ? 'bg-primary text-white' : (dark ? 'bg-dark-700 text-gray-400 hover:text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200')">
            Invoice Settings
        </button>
    </div>

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" id="settingsForm">
        @csrf

        {{-- Studio Info Tab --}}
        <div x-show="tab === 'studio'" class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Studio Information</h3>

            {{-- Logo Upload --}}
            @php $currentLogo = \App\Models\Setting::logoUrl(); @endphp
            <div class="mb-6">
                <label class="text-gray-400 text-xs mb-2 block">Studio Logo</label>
                <div class="flex items-center gap-4">
                    {{-- Preview --}}
                    <div class="w-20 h-20 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden bg-white"
                         :style="dark ? 'border:1px solid #2d3154' : 'border:1px solid #e5e7eb'">
                        <img id="logoPreview" src="{{ $currentLogo ?? '' }}" class="w-full h-full object-contain {{ $currentLogo ? '' : 'hidden' }}"/>
                        <svg id="logoPlaceholder" class="w-8 h-8 text-gray-300 {{ $currentLogo ? 'hidden' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    {{-- Upload button --}}
                    <div class="flex-1">
                        {{-- Visible input (JS only — opens crop modal) --}}
                        <input type="file" accept="image/*" id="logoInput" class="hidden"/>
                        {{-- Hidden input (cropped result — actually submitted) --}}
                        <input type="file" name="logo" id="logoFile" class="hidden"/>
                        <label for="logoInput"
                               class="inline-block cursor-pointer text-sm font-medium px-4 py-2 rounded-lg transition-colors"
                               :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                            Choose Logo
                        </label>
                        <p class="text-gray-500 text-xs mt-2">PNG, JPG, SVG · Crop to square · Max 4MB</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Studio Name</label>
                    <input type="text" name="studio_name" value="{{ $settings['studio_name'] ?? 'Creative Studio' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Tagline</label>
                    <input type="text" name="studio_tagline" value="{{ $settings['studio_tagline'] ?? 'Photography & Films' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Email</label>
                    <input type="email" name="email" value="{{ $settings['email'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="info@creativestudio.lk"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Website</label>
                    <input type="text" name="website" value="{{ $settings['website'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="www.creativestudio.lk"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Phone</label>
                    <input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="077 123 4567"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Phone 2</label>
                    <input type="text" name="phone2" value="{{ $settings['phone2'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="011 234 5678"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Address</label>
                    <input type="text" name="address" value="{{ $settings['address'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="No. 123, Main Street"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">City</label>
                    <input type="text" name="city" value="{{ $settings['city'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="Badulla"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Currency</label>
                    <input type="text" name="currency" value="{{ $settings['currency'] ?? 'Rs.' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="Rs."/>
                </div>
                <div></div>
            </div>
        </div>

        {{-- Bank Details Tab --}}
        <div x-show="tab === 'bank'" x-cloak class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Bank Account Details</h3>
            <p class="text-gray-400 text-xs mb-4">These details will appear on invoices for client payments.</p>

            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Bank Name</label>
                <input type="text" name="bank_name" value="{{ $settings['bank_name'] ?? '' }}"
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                       placeholder="e.g. Commercial Bank"/>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Account Number</label>
                    <input type="text" name="bank_account" value="{{ $settings['bank_account'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="1234567890"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Branch</label>
                    <input type="text" name="bank_branch" value="{{ $settings['bank_branch'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="Badulla"/>
                </div>
            </div>
        </div>

        {{-- Invoice Settings Tab --}}
        <div x-show="tab === 'invoice'" x-cloak class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Invoice Settings</h3>

            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Invoice Prefix</label>
                <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'INV' }}"
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                       placeholder="INV"/>
                <p class="text-gray-500 text-xs mt-1">Example: INV-2026-0001</p>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Invoice Footer Note</label>
                <textarea name="invoice_footer" rows="3"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                          placeholder="Thank you for your business!">{{ $settings['invoice_footer'] ?? '' }}</textarea>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end mt-4">
            <button type="submit"
                    class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                Save Settings
            </button>
        </div>
    </form>
</div>

{{-- Crop Modal --}}
<div id="cropModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="rounded-xl w-full max-w-md mx-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="p-4 flex items-center justify-between" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
            <h2 class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Crop Logo</h2>
            <button type="button" onclick="closeCropModal()" class="text-gray-400 hover:text-red-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-4">
            <div class="max-h-80 overflow-hidden">
                <img id="cropImage" class="max-w-full"/>
            </div>
        </div>
        <div class="p-4 flex gap-3" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
            <button type="button" onclick="closeCropModal()"
                    class="flex-1 text-sm font-medium py-2 rounded-lg transition-colors"
                    :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                Cancel
            </button>
            <button type="button" onclick="applyCrop()"
                    class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
                Apply Crop
            </button>
        </div>
    </div>
</div>

<script>
let cropper = null;

document.getElementById('logoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(event) {
        const cropImage = document.getElementById('cropImage');
        cropImage.src = event.target.result;
        document.getElementById('cropModal').classList.remove('hidden');

        if (cropper) cropper.destroy();
        cropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 1,
            background: false,
            autoCropArea: 1,
        });
    };
    reader.readAsDataURL(file);
});

function closeCropModal() {
    document.getElementById('cropModal').classList.add('hidden');
    if (cropper) { cropper.destroy(); cropper = null; }
    document.getElementById('logoInput').value = '';
}

function applyCrop() {
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400,
        imageSmoothingQuality: 'high',
    });

    canvas.toBlob(function(blob) {
        // Cropped blob එක hidden file input එකට දානවා
        const croppedFile = new File([blob], 'logo.png', { type: 'image/png' });
        const dt = new DataTransfer();
        dt.items.add(croppedFile);
        document.getElementById('logoFile').files = dt.files;

        // Preview එක update කරනවා
        const previewUrl = URL.createObjectURL(blob);
        const preview = document.getElementById('logoPreview');
        preview.src = previewUrl;
        preview.classList.remove('hidden');
        document.getElementById('logoPlaceholder').classList.add('hidden');

        closeCropModal();
    }, 'image/png');
}
</script>

@endsection