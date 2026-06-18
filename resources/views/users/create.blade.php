@extends('layouts.app')

@section('title', 'Add User')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css" rel="stylesheet"/>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Add User</h1>
            <p class="text-gray-400 text-sm">User Management &gt; New User</p>
        </div>
    </div>

    <div class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'"
         x-data="{ showPass: false, showConfirm: false }">

        @if($errors->any())
        <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" id="userForm">
            @csrf

            {{-- Avatar --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center flex-shrink-0 bg-primary">
                    <img id="avatarPreview" class="w-full h-full object-cover hidden"/>
                    <svg id="avatarIcon" class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <label class="cursor-pointer text-sm font-medium px-4 py-2 rounded-lg transition-colors inline-block"
                           :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                        Upload Photo
                        <input type="file" id="avatarInput" accept="image/*" class="hidden"/>
                    </label>
                    <p class="text-gray-500 text-xs mt-1">JPG, PNG or WEBP. Max 2MB.</p>
                </div>
            </div>

            {{-- Hidden file field for cropped image --}}
            <input type="file" name="avatar" id="avatarFile" accept="image/*" class="hidden"/>

            {{-- Name & Email --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="John Perera"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="john@creativestudio.lk"/>
                </div>
            </div>

            {{-- Password --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Password *</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" required
                               class="w-full text-sm rounded-lg px-3 py-2 pr-10 focus:outline-none focus:border-primary"
                               :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                               placeholder="Min 8 characters"/>
                        <button type="button" @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-300">
                            <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Confirm Password *</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                               class="w-full text-sm rounded-lg px-3 py-2 pr-10 focus:outline-none focus:border-primary"
                               :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                               placeholder="Repeat password"/>
                        <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-300">
                            <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Role & Position --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Role *</label>
                    <select name="role" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Position</label>
                    <input type="text" name="position" value="{{ old('position') }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="e.g. Photographer"/>
                </div>
            </div>

            {{-- Phone --}}
            <div class="mb-6">
                <label class="text-gray-400 text-xs mb-1 block">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                       placeholder="077 123 4567"/>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <a href="{{ route('users.index') }}"
                   class="flex-1 text-center text-sm font-medium py-2 rounded-lg transition-colors"
                   :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Create User
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
    const icon = document.getElementById('avatarIcon');
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
            icon.classList.add('hidden');

            modal.classList.add('hidden');
            cropper.destroy();
            cropper = null;
        }, 'image/png');
    });
})();
</script>

@endsection