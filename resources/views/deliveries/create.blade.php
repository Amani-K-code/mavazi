@extends('layouts.app')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    {{-- Error Reporting --}}
    @if ($errors->any())
        <div class="bg-red-600 text-white p-4 rounded-2xl shadow-lg font-bold mb-6">
            <p class="uppercase tracking-widest text-xs mb-2">Please fix the following:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('storekeeper.deliveries.store') }}" method="POST" class="space-y-8">
        @csrf

        {{-- 1. Delivery Header Section --}}
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-slate-700 overflow-hidden">
            <div class="bg-[#0f172a] p-8 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-black uppercase tracking-tighter">Register New Delivery</h2>
                    <p class="text-blue-200/60 font-medium">Add quantities and prices to calculate total cost.</p>
                </div>
                
                <div class="flex space-x-4">
                    {{-- Option 1: Normal Submit --}}
                    <button type="submit" name="submit_only" class="bg-white/10 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-white/20 transition border border-white/20">
                        Submit Only
                    </button>
                    {{-- Option 2: Submit + PDF --}}
                    <button type="submit" name="submit_with_pdf" value="1" class="bg-logos-gold text-logos-blue px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:scale-105 transition shadow-lg flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i> Submit & Download PDF
                    </button>
                </div>

            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6 border-b border-gray-100 dark:border-slate-700">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Delivery Date</label>
                    <input type="date" name="delivery_date" required class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl px-5 py-4 font-bold text-logos-blue focus:ring-2 focus:ring-logos-gold">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Payment Due Date</label>
                    <input type="date" name="payment_due_date" required class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl px-5 py-4 font-bold text-logos-blue focus:ring-2 focus:ring-logos-gold">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Invoice Amount (Ksh) - <span class="text-logos-gold">Calculated</span></label>
                    <input type="number" name="total_invoice_amount" id="total_invoice_amount" step="0.01" readonly required class="w-full bg-slate-100 dark:bg-slate-950 border-none rounded-2xl px-5 py-4 font-black text-logos-blue focus:ring-0 cursor-not-allowed" placeholder="0.00">
                </div>
            </div>
        </div>

        {{-- Container for items added via Modal --}}
        <div id="new-items-registry" class="px-4 space-y-3 empty:hidden">
            <h3 class="text-sm font-black text-logos-blue dark:text-logos-gold uppercase tracking-widest mb-2">New Items to be Added:</h3>
        </div>

        {{-- 2. "Add New Item" Global Button --}}
        <div class="flex justify-between items-center px-4">
            <h3 class="text-xl font-black text-logos-blue dark:text-white uppercase">Select Items Delivered</h3>
            <button type="button" onclick="openNewItemModal()" class="px-6 py-3 bg-logos-blue text-white rounded-xl font-black uppercase text-xs tracking-widest hover:bg-logos-gold transition shadow-md">
                <i class="fas fa-plus mr-2 text-logos-gold"></i> Create Completely New Item
            </button>
        </div>

        {{-- 3. The Inventory Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 px-4">
            @foreach($items as $itemName => $variants)
            <div class="item-card bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col overflow-hidden relative">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/40 flex justify-between items-start">
                    <div>
                        <h3 class="font-black text-lg text-logos-blue dark:text-white uppercase leading-tight">{{ $itemName }}</h3>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-1">{{ $variants->count() }} Sizes Available</p>
                    </div>
                    <button type="button" onclick="openNewSizeModal('{{ addslashes($itemName) }}')" class="text-logos-blue hover:text-logos-gold transition">
                        <i class="fas fa-plus-circle fa-lg"></i>
                    </button>
                </div>

                <div class="p-4 space-y-3 max-h-[400px] overflow-y-auto custom-scrollbar">
                    @foreach($variants as $variant)
                    <div class="row-item flex items-center justify-between p-4 rounded-2xl border border-gray-50 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-logos-blue dark:text-white uppercase">{{ $variant->size_label }}</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Stock: {{ $variant->stock_quantity }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="hidden" name="items[{{ $variant->id }}][inventory_id]" value="{{ $variant->id }}">
                            
                            {{-- Price Input --}}
                            <input type="number" name="items[{{ $variant->id }}][price]" step="0.01" oninput="calculateTotal()" 
                                class="line-price w-20 h-10 bg-slate-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg px-2 text-[10px] font-bold text-center focus:ring-1 focus:ring-logos-blue"
                                placeholder="Price">
                            
                            {{-- Quantity Input --}}
                            <input type="number" name="items[{{ $variant->id }}][quantity]" min="0" oninput="calculateTotal()"
                                class="line-qty w-16 h-10 bg-slate-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg px-2 text-sm font-black text-center focus:ring-2 focus:ring-logos-blue"
                                placeholder="Qty">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </form>
</div>

{{-- MODAL STRUCTURE --}}
<div id="newItemModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 w-full max-w-md shadow-2xl">
        <h3 id="modalTitle" class="text-2xl font-black text-logos-blue dark:text-white uppercase mb-4">New Entry</h3>
        <div class="space-y-4">
            <input type="text" id="modal_item_name" class="w-full bg-slate-50 dark:bg-slate-900 rounded-xl p-4 font-bold border-none" placeholder="Item Name">
            <input type="text" id="modal_item_size" class="w-full bg-slate-50 dark:bg-slate-900 rounded-xl p-4 font-bold border-none" placeholder="Size (e.g. XL)">
            <input type="number" id="modal_item_price" step="0.01" class="w-full bg-slate-50 dark:bg-slate-900 rounded-xl p-4 font-bold border-none" placeholder="Unit Price (Ksh)">
            <div class="flex space-x-3 mt-4">
                <button type="button" onclick="closeModal()" class="flex-1 text-gray-400 font-bold uppercase text-xs">Cancel</button>
                <button type="button" onclick="confirmNewItem()" class="flex-1 bg-logos-blue text-white py-4 rounded-xl font-bold uppercase text-xs">Add to List</button>
            </div>
        </div>
    </div>
</div>

<script>
    let newItemCount = 0;

    function calculateTotal() {
        let grandTotal = 0;
        // Target all rows in the grid and the registry
        const items = document.querySelectorAll('.row-item');
        
        items.forEach(item => {
            const priceInput = item.querySelector('.line-price');
            const qtyInput = item.querySelector('.line-qty');
            
            if (priceInput && qtyInput) {
                const price = parseFloat(priceInput.value) || 0;
                const qty = parseInt(qtyInput.value) || 0;
                grandTotal += (price * qty);
            }
        });

        document.getElementById('total_invoice_amount').value = grandTotal.toFixed(2);
    }

    function openNewItemModal() {
        document.getElementById('modalTitle').innerText = "New Product";
        document.getElementById('modal_item_name').value = '';
        document.getElementById('modal_item_price').value = '';
        document.getElementById('modal_item_name').readOnly = false;
        document.getElementById('newItemModal').classList.remove('hidden');
    }

    function openNewSizeModal(itemName) {
        document.getElementById('modalTitle').innerText = "Add Size to " + itemName;
        document.getElementById('modal_item_name').value = itemName;
        document.getElementById('modal_item_price').value = '';
        document.getElementById('modal_item_name').readOnly = true;
        document.getElementById('newItemModal').classList.remove('hidden');
    }

    function closeModal() { document.getElementById('newItemModal').classList.add('hidden'); }
    
    function confirmNewItem() {
        const name = document.getElementById('modal_item_name').value;
        const size = document.getElementById('modal_item_size').value;
        const price = document.getElementById('modal_item_price').value;
        
        if(!name || !size || !price) return alert("Fill all fields including price");

        const html = `
            <div class="row-item flex items-center justify-between bg-white dark:bg-slate-800 p-4 rounded-2xl border-2 border-logos-blue/20 shadow-sm">
                <span class="font-black text-logos-blue dark:text-white uppercase text-sm">${name} (${size})</span>
                <div class="flex items-center space-x-3">
                    <input type="hidden" name="items[new_${newItemCount}][item_name]" value="${name}">
                    <input type="hidden" name="items[new_${newItemCount}][size]" value="${size}">
                    
                    <input type="number" name="items[new_${newItemCount}][price]" value="${price}" step="0.01" 
                        oninput="calculateTotal()" class="line-price w-20 h-10 bg-slate-100 dark:bg-slate-900 rounded-lg text-center font-bold border-none text-[10px]">
                    
                    <input type="number" name="items[new_${newItemCount}][quantity]" value="1" min="1" 
                        oninput="calculateTotal()" class="line-qty w-20 h-10 bg-slate-100 dark:bg-slate-900 rounded-lg text-center font-bold border-none">
                    
                    <button type="button" onclick="this.parentElement.parentElement.remove(); calculateTotal();" class="text-red-500"><i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        document.getElementById('new-items-registry').insertAdjacentHTML('beforeend', html);
        newItemCount++;
        calculateTotal(); // Update total after adding new item
        closeModal();
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
@endsection