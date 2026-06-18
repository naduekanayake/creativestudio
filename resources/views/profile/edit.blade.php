@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css" rel="stylesheet"/>

<div class="max-w-3xl mx-auto" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">My Profile</h1>
        <p class="text-gray-400 text-sm mt-0.5">Manage your account information</p>
    </div>

    @if(session('success'))
    <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- Profile Info --}}
    <div class="rounded-xl p-6 mb-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Profile Information</h3>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PATCH')

            {{-- Avatar --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center flex-shrink-0 bg-primary">
                    <img id="avatarPreview" src="{{ $user->avatar_url }}"
                         class="w-full h-full object-cover {{ $user->avatar ? '' : 'hidden' }}"/>
                    <span id="avatarInitial" class="text-white text-2xl font-bold {{ $user->avatar ? 'hidden' : '' }}">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div>
                    <label class="cursor-pointer text-sm font-medium px-4 py-2 rounded-lg transition-colors inline-block"
                           :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                        Change Photo
                        <input type="file" id="avatarInput" accept="image/*" class="hidden"/>
                    </label>
                    <p class="text-gray-500 text-xs mt-1">JPG, PNG or WEBP. Max 2MB.</p>
                </div>
            </div>

            {{-- Hidden field that holds cropped image --}}
            <input type="hidden" name="avatar_cropped" id="avatarCropped"/>
            <input type="file" name="avatar" id="avatarFile" accept="image/*" class="hidden"/>

            @if($errors->any())
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Full Name *</label>
                    <input type="text" name="name" value="{{ $user->name }}" required
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Email *</label>
                    <input type="email" name="email" value="{{ $user->email }}" required
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Phone</label>
                    <input type="text" name="phone" value="{{ $user->phone }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="077 123 4567"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Position</label>
                    <input type="text" name="position" value="{{ $user->position }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="e.g. Studio Owner"/>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-6 py-2 rounded-lg transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Change Password</h3>

        <form method="POST" action="{{ route('profile.password') }}">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Current Password *</label>
                <div class="relative">
                    <input :type="showCurrent ? 'text' : 'password'" name="current_password" required
                           class="w-full text-sm rounded-lg px-3 py-2 pr-10 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                    <button type="button" @click="showCurrent = !showCurrent" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-300">
                        <svg x-show="!showCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">New Password *</label>
                    <div class="relative">
                        <input :type="showNew ? 'text' : 'password'" name="password" required
                               class="w-full text-sm rounded-lg px-3 py-2 pr-10 focus:outline-none focus:border-primary"
                               :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                               placeholder="Min 8 characters"/>
                        <button type="button" @click="showNew = !showNew" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-300">
                            <svg x-show="!showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Confirm New Password *</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                               class="w-full text-sm rounded-lg px-3 py-2 pr-10 focus:outline-none focus:border-primary"
                               :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                               placeholder="Repeat new password"/>
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-300">
                            <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-6 py-2 rounded-lg transition-colors">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Crop Modal --}}
<div id="cropModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="rounded-xl w-full max-w-md" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="p-4" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
            <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">Adjust Photo</h3>
            <p class="text-gray-400 text-xs mt-0.5">Drag to move · scroll to zoom</p>
        </div>
        <div class="p-4">
            <div class="max-h-80 overflow-hidden">
                <img id="cropImage" class="max-w-full block"/>
            </div>
        </div>
        <div class="p-4 flex gap-3" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
            <button type="button" id="cropCancel"
                    class="flex-1 text-sm font-medium py-2 rounded-lg transition-colors"
                    :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                Cancel
            </button>
            <button type="button" id="cropConfirm"
                    class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
                Apply
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js"></script>
<script>
(function() {
    const input = document.getElementById('avatarInput');
    const modal = document.getElementById('cropModal');
    const cropImage = document.getElementById('cropImage');
    const preview = document.getElementById('avatarPreview');
    const initial = document.getElementById('avatarInitial');
    const croppedField = document.getElementById('avatarCropped');
    const fileField = document.getElementById('avatarFile');
    let cropper = null;

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('Image must be under 2MB');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(ev) {
            cropImage.src = ev.target.result;
            modal.classList.remove('hidden');
            if (cropper) cropper.destroy();
            cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                cropBoxResizable: false,
                cropBoxMovable: false,
            });
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('cropCancel').addEventListener('click', function() {
        modal.classList.add('hidden');
        input.value = '';
        if (cropper) { cropper.destroy(); cropper = null; }
    });

    document.getElementById('cropConfirm').addEventListener('click', function() {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
        canvas.toBlob(function(blob) {
            const croppedFile = new File([blob], 'avatar.png', { type: 'image/png' });
            const dt = new DataTransfer();
            dt.items.add(croppedFile);
            fileField.files = dt.files;

            const url = canvas.toDataURL('image/png');
            preview.src = url;
            preview.classList.remove('hidden');
            if (initial) initial.classList.add('hidden');

            modal.classList.add('hidden');
            cropper.destroy();
            cropper = null;
        }, 'image/png');
    });
})();
</script>

@endsection