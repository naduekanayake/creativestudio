@extends('layouts.app')

@section('title', 'Packages')

@section('content')

<div x-data="packageSearch()">

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Packages</h1>
        <p class="text-gray-400 text-sm mt-0.5">Create and manage your photography & videography service packages.</p>
    </div>
    <button onclick="document.getElementById('addPackageModal').classList.remove('hidden')"
            class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Package
    </button>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-purple-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['total'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Packages</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-blue-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['active'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Active Packages</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-green-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format((float) $stats['avg_price']) }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Avg. Package Price</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-orange-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['categories'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Categories</p>
    </div>
</div>

{{-- Packages Table --}}
<div class="rounded-xl" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
    <div class="p-4 flex items-center justify-between" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
        <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">All Packages</h3>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" x-model="search" placeholder="Search packages..."
                   class="text-sm rounded-lg pl-9 pr-3 py-1.5 w-56 focus:outline-none focus:border-primary"
                   :style="dark ? 'background:#252840;color:#d1d5db;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
        </div>
    </div>

    <table class="w-full">
        <thead>
            <tr class="text-gray-500 text-xs" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                <th class="text-left px-4 py-3">PACKAGE NAME</th>
                <th class="text-left px-4 py-3">CATEGORY</th>
                <th class="text-left px-4 py-3">PRICE</th>
                <th class="text-left px-4 py-3">FEATURES</th>
                <th class="text-left px-4 py-3">STATUS</th>
                <th class="text-left px-4 py-3">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($packages as $package)
            <tr class="searchable-row"
                data-search="{{ strtolower($package->name . ' ' . $package->category . ' ' . $package->description) }}"
                x-show="matches('{{ strtolower($package->name . ' ' . $package->category . ' ' . $package->description) }}')"
                :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                             :style="dark ? 'background:#252840' : 'background:#f3f4f6'">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $package->name }}</p>
                            @if($package->description)
                            <p class="text-gray-500 text-xs">{{ Str::limit($package->description, 40) }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $package->category_color }}">
                        {{ $package->category }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $package->formatted_price }}</p>
                    <p class="text-gray-500 text-xs">Starting from</p>
                </td>
                <td class="px-4 py-3 text-gray-400 text-sm">
                    {{ $package->features ? count($package->features) : 0 }} Items
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs
                        {{ $package->status === 'Active' ? 'bg-green-500/20 text-green-400' :
                           ($package->status === 'Draft' ? 'bg-gray-500/20 text-gray-400' :
                           'bg-red-500/20 text-red-400') }}">
                        {{ $package->status }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <button type="button"
                                onclick="openEditModal(this)"
                                data-id="{{ $package->id }}"
                                data-name="{{ $package->name }}"
                                data-category="{{ $package->category }}"
                                data-price="{{ $package->price }}"
                                data-description="{{ $package->description }}"
                                data-features="{{ $package->features ? implode(chr(10), $package->features) : '' }}"
                                data-status="{{ $package->status }}"
                                class="text-gray-400 hover:text-primary transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('packages.destroy', $package) }}"
                              onsubmit="return confirm('Delete this package?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-400 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                    No packages yet. Create your first package!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- No search results --}}
    <div x-show="search.length > 0 && visibleCount() === 0" class="px-4 py-8 text-center text-gray-500 text-sm">
        No packages match "<span x-text="search"></span>"
    </div>

    @if($packages->hasPages())
    <div class="px-4 py-3" x-show="search.length === 0" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
        {{ $packages->links() }}
    </div>
    @endif
</div>

{{-- Add/Edit Package Modal --}}
<div id="addPackageModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="rounded-xl w-full max-w-lg mx-4 max-h-screen overflow-y-auto"
         :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">

        <div class="p-5 flex items-center justify-between" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
            <h2 id="modalTitle" class="font-semibold text-lg" :class="dark ? 'text-white' : 'text-gray-900'">New Package</h2>
            <button type="button" onclick="closePackageModal()" class="text-gray-400 hover:text-red-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="packageForm" method="POST" action="{{ route('packages.store') }}" class="p-5 space-y-4">
            @csrf
            <div id="methodField"></div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Package Name *</label>
                <input type="text" name="name" id="pkg_name" required
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                       placeholder="e.g. Wedding Photography Premium"/>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Category *</label>
                    <select name="category" id="pkg_category" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        <option value="Photography">Photography</option>
                        <option value="Videography">Videography</option>
                        <option value="Combo">Combo</option>
                    </select>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Price (Rs.) *</label>
                    <input type="number" name="price" id="pkg_price" required min="0" step="0.01"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="150000"/>
                </div>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Description</label>
                <textarea name="description" id="pkg_description" rows="2"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                          placeholder="Brief description of the package"></textarea>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Features (one per line)</label>
                <textarea name="features" id="pkg_features" rows="4"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                          placeholder="Full Day Coverage (Up to 12 Hours)&#10;2 Professional Photographers&#10;Edited Photos (High Resolution)"></textarea>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Status</label>
                <select name="status" id="pkg_status"
                        class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                        :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                    <option value="Active">Active</option>
                    <option value="Draft">Draft</option>
                    <option value="Archived">Archived</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closePackageModal()"
                        class="flex-1 text-sm font-medium py-2 rounded-lg transition-colors"
                        :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Save Package
                </button>
            </div>
        </form>
    </div>
</div>

</div>

<script>
function packageSearch() {
    return {
        search: '',
        matches(text) {
            if (this.search.length === 0) return true;
            return text.includes(this.search.toLowerCase());
        },
        visibleCount() {
            if (this.search.length === 0) return 1;
            const rows = document.querySelectorAll('.searchable-row');
            let count = 0;
            rows.forEach(row => {
                if (row.dataset.search.includes(this.search.toLowerCase())) count++;
            });
            return count;
        }
    }
}

function closePackageModal() {
    document.getElementById('addPackageModal').classList.add('hidden');
    document.getElementById('packageForm').reset();
    document.getElementById('packageForm').action = "{{ route('packages.store') }}";
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('modalTitle').innerText = 'New Package';
}

function openEditModal(btn) {
    document.getElementById('modalTitle').innerText = 'Edit Package';
    document.getElementById('pkg_name').value = btn.dataset.name;
    document.getElementById('pkg_category').value = btn.dataset.category;
    document.getElementById('pkg_price').value = btn.dataset.price;
    document.getElementById('pkg_description').value = btn.dataset.description;
    document.getElementById('pkg_features').value = btn.dataset.features;
    document.getElementById('pkg_status').value = btn.dataset.status;

    document.getElementById('packageForm').action = '/packages/' + btn.dataset.id;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

    document.getElementById('addPackageModal').classList.remove('hidden');
}
</script>

@endsection