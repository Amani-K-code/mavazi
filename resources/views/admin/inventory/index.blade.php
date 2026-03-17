@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-in fade-in duration-500">
    {{-- Header Section: Kept your original search and layout --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-3xl font-black text-logos-blue dark:text-white tracking-tight">Global Inventory</h2>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-logos-gold">Manage Pricing & Stock Levels</p>
        </div>
        
        <div class="flex flex-wrap gap-3 w-full md:w-auto">
            <div class="relative flex-1 md:w-64">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" id="inventorySearch" name="search" value="{{ request('search') }}" placeholder="Search items..." 
                    class="w-full bg-white/10 border border-white/10 rounded-xl py-2 pl-10 pr-10 text-xs text-logos-blue dark:text-white outline-none focus:border-logos-gold transition"
                    oninput="handleSearch(this.value)">
                
                <button id="clearSearch" onclick="clearSearchInput()" 
                    class="{{ request('search') ? '' : 'hidden' }} absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>

            <button onclick="document.getElementById('bulkDiscountModal').showModal()" class="glass-card px-4 py-2 rounded-xl text-xs font-bold text-amber-500 hover:bg-amber-500/10 transition">
                <i class="fas fa-percentage mr-2"></i> Bulk Discount
            </button>
            <button onclick="document.getElementById('addItemModal').showModal()" class="btn-gold"><i class="fas fa-plus mr-2"></i> Add Item</button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-500 p-4 rounded-2xl text-xs font-bold animate-bounce">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @forelse($inventories as $category => $items)
    <div class="space-y-4">
        <div class="flex items-center gap-4">
            <h3 class="font-black text-logos-blue dark:text-logos-gold uppercase tracking-widest text-xs">{{ $category }}</h3>
            <div class="h-[1px] flex-1 bg-gray-200 dark:bg-white/5"></div>
            <span class="text-[10px] font-bold text-gray-500">{{ $items->count() }} Items</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($items as $item)
            @php
                // Preserve your original auto-icon detection logic
                $name = strtolower($item->item_name);
                $image = null;
                $icon = 'fa-shirt'; 
                $customSvg = null;

                if (str_contains($name, 'peter pan')) { $image = 'peter_pan.png'; } 
                elseif (str_contains($name, 'sweater')) { $image = 'sweater.png'; } 
                elseif (str_contains($name, 'fleece') || str_contains($name, 'jacket')) { $image = 'fleece-hoodie.png'; } 
                elseif (str_contains($name, 'track suit')) { $image = 'Tracksuit.png'; } 
                elseif (str_contains($name, 'trunks')) { $image = 'swimming_trunk_boys.png'; } 
                elseif (str_contains($name, 'swim suit') || str_contains($name, 'costume')) { $image = 'ladies_swimsuit.png'; } 
                elseif (str_contains($name, 'short')) { $image = 'Games_shorts.png'; } 
                elseif (str_contains($name, 'yellow pin stripped') || str_contains($name, 'sky blue')) { $image = 'Tie_collar.png'; } 
                elseif (str_contains($name, 'trouser')) { $customSvg = '<path d="M6 2h12l3 20h-7v-8h-4v8H3L6 2z" />'; } 
                elseif (str_contains($name, 'skort') || str_contains($name, 'skirt')) { $customSvg = '<path d="M8 2h8l5 20H3L8 2z" />'; } 
                elseif (str_contains($name, 'tie')) { $customSvg = '<path d="M6 2l4 4-3 11 5 5 5-5-3-11 4-4H6z" />'; } 
                elseif (str_contains($name, 'blazer')) { $icon = 'fa-user-tie'; } 
                elseif (str_contains($name, 'socks') || str_contains($name, 'stocking')) { $icon = 'fa-socks'; }

                // Check if a specific image was picked during creation/edit
                if($item->image_path) { $image = $item->image_path; }
            @endphp

            <div class="glass-card p-6 rounded-[2rem] border border-gray-100 dark:border-white/5 hover:border-logos-gold/30 transition group relative overflow-hidden bg-white dark:bg-slate-900/50">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-logos-blue overflow-hidden group-hover:scale-110 transition">
                            @if($image)
                                <img src="{{ asset('images/uniforms/' . $image) }}" 
                                    alt="{{ $item->item_name }}" 
                                    class="w-10 h-10 object-contain grayscale brightness-0 opacity-80 dark:invert">
                            @elseif($customSvg)
                                <svg class="w-7 h-7 text-slate-900 dark:text-white" viewBox="0 0 24 24" fill="currentColor">
                                    {!! $customSvg !!}
                                </svg>
                            @else
                                <i class="fas {{ $icon }} text-xl text-slate-900 dark:text-white"></i>
                            @endif
                        </div>
                        <div>
                            <p class="font-black text-logos-blue dark:text-white leading-tight">{{ $item->item_name }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-tighter">Size: {{ $item->size_label }}</p>
                        </div>
                    </div>
                    {{-- FIXED: Button now triggers the Edit Modal with the item data --}}
                    <button onclick="openEditModal({{ json_encode($item) }})" class="text-gray-400 hover:text-logos-gold transition">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>

                <div class="mt-6 flex justify-between items-end">
                    <div>
                        <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase">Current Stock</p>
                        <p class="text-xl font-black {{ $item->stock_quantity <= $item->low_stock_threshold ? 'text-red-500' : 'text-logos-blue dark:text-white' }}">
                            {{ $item->stock_quantity }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase">Unit Price</p>
                        <p class="text-lg font-black text-logos-gold tracking-tighter">KSh {{ number_format($item->price) }}</p>
                    </div>
                </div>
                
                @if($item->stock_quantity <= $item->low_stock_threshold)
                <div class="absolute top-0 right-0 bg-red-500 text-[8px] font-black text-white px-3 py-1 rounded-bl-xl uppercase tracking-widest">
                    Low Stock
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="glass-card p-20 rounded-[2.5rem] text-center border border-gray-100 dark:border-white/5">
        <i class="fas fa-box-open text-4xl text-gray-300 dark:text-gray-700 mb-4"></i>
        <p class="text-gray-500 font-bold">No items found matching your criteria.</p>
    </div>
    @endforelse
</div>

{{-- ADD ITEM MODAL: Enhanced with datalist for new categories --}}
<dialog id="addItemModal" class="modal bg-slate-950/80 backdrop-blur-sm">
    <div class="glass-card p-8 rounded-[2.5rem] w-full max-w-md border border-white/10 mx-auto mt-20 shadow-2xl bg-slate-900">
        <h3 class="font-black text-xl mb-6 text-white flex items-center gap-3">
            <i class="fas fa-plus-circle text-logos-gold"></i> Add New Inventory
        </h3>
        <form action="{{ route('admin.inventory.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Item Name</label>
                <input type="text" name="item_name" required placeholder="e.g. Leather School Shoes" 
                    class="w-full bg-white/5 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-logos-gold transition">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Category (Pick or Type New)</label>
                <input list="categories" name="category" required class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-logos-gold transition">
                <datalist id="categories">
                    @foreach($inventories->keys() as $cat)
                        <option value="{{ $cat }}">
                    @endforeach
                </datalist>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Size Label</label>
                    <input type="text" name="size_label" required placeholder="e.g. Size 8" 
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-logos-gold transition">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Price (KSh)</label>
                    <input type="number" name="price" required 
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-logos-gold transition">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Initial Stock</label>
                    <input type="number" name="stock_quantity" required 
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-logos-gold transition">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Low Stock Alert</label>
                    <input type="number" name="low_stock_threshold" value="5" required 
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-logos-gold transition">
                </div>
            </div>

            <div class="pt-6 flex gap-3">
                <button type="button" onclick="this.closest('dialog').close()" class="flex-1 p-3 rounded-xl border border-white/10 font-bold text-xs text-white hover:bg-white/5 transition tracking-widest">CANCEL</button>
                <button type="submit" class="flex-1 bg-logos-gold text-logos-blue p-3 rounded-xl font-black text-xs tracking-widest hover:scale-105 transition shadow-lg shadow-logos-gold/20">SAVE TO VAULT</button>
            </div>
        </form>
    </div>
</dialog>

{{-- EDIT ITEM MODAL: Added Delete functionality --}}
<dialog id="editItemModal" class="modal bg-slate-950/80 backdrop-blur-sm">
    <div class="glass-card p-8 rounded-[2.5rem] w-full max-w-md border border-white/10 mx-auto mt-20 bg-slate-900 text-white shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-black text-xl">Edit Item</h3>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Delete this item permanently?')" class="text-red-500 hover:text-red-400 text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-trash mr-1"></i> Delete
                </button>
            </form>
        </div>
        
        <form id="editForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Item Name</label>
                <input type="text" id="edit_name" name="item_name" class="w-full bg-white/5 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-logos-gold transition">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Price (KSh)</label>
                    <input type="number" id="edit_price" name="price" class="w-full bg-white/5 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Stock Quantity</label>
                    <input type="number" id="edit_stock" name="stock_quantity" class="w-full bg-white/5 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none">
                </div>
            </div>

            <div class="pt-6 flex gap-3">
                <button type="button" onclick="this.closest('dialog').close()" class="flex-1 p-3 rounded-xl border border-white/10 font-bold text-xs">CANCEL</button>
                <button type="submit" class="flex-1 bg-logos-gold text-logos-blue p-3 rounded-xl font-black text-xs">UPDATE ITEM</button>
            </div>
        </form>
    </div>
</dialog>

{{-- BULK DISCOUNT MODAL: Kept exactly as your original --}}
<dialog id="bulkDiscountModal" class="modal bg-slate-950/80 backdrop-blur-sm">
    <div class="glass-card p-8 rounded-[2.5rem] w-96 max-w-lg border border-white/10 mx-auto mt-20 bg-slate-900">
        <h3 class="font-black text-xl mb-4 text-white">Apply Bulk Discount</h3>
        <form action="{{ route('admin.inventory.discount') }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400">Category</label>
                <select name="category" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-logos-gold">
                    @foreach($inventories->keys() as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400">Discount Percentage (%)</label>
                <input type="number" name="percentage" class="w-full bg-white/5 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-logos-gold" placeholder="e.g. 10">
            </div>
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="this.closest('dialog').close()" class="flex-1 p-3 rounded-xl border border-white/10 font-bold text-xs text-white">CANCEL</button>
                <button type="submit" class="flex-1 bg-logos-gold text-logos-blue p-3 rounded-xl font-black text-xs">APPLY NOW</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    // Merged Search Logic
    let searchTimeout;

    function handleSearch(query) {
        const clearBtn = document.getElementById('clearSearch');
        if (query.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const url = new URL(window.location.href);
            url.searchParams.set('search', query);
            window.location.href = url.toString();
        }, 600);
    }

    function clearSearchInput() {
        const input = document.getElementById('inventorySearch');
        input.value = '';
        document.getElementById('clearSearch').classList.add('hidden');
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        window.location.href = url.toString();
    }

    // Modal populate logic
    function openEditModal(item) {
        const modal = document.getElementById('editItemModal');
        const editForm = document.getElementById('editForm');
        const deleteForm = document.getElementById('deleteForm');
        
        editForm.action = `/admin/inventory/${item.id}`;
        deleteForm.action = `/admin/inventory/${item.id}`;
        
        document.getElementById('edit_name').value = item.item_name;
        document.getElementById('edit_price').value = item.price;
        document.getElementById('edit_stock').value = item.stock_quantity;
        
        modal.showModal();
    }
</script>
@endsection