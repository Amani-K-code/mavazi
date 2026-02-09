@extends('layouts.app')

@section('content')
    <div x-data= "cartSystem()" class="flex flex-col lg:flex-row gap-8">
        
        <div class="flex-1 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-black text-logos-blue uppercase tracking-tight">Uniform Catalog</h2>
                
                <form id="searchForm" action="{{ route('cashier.dashboard') }}" method="GET" class="relative">
                    <input type="hidden" name="category" value="{{ request('category', 'ALL') }}">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" id="searchInput" placeholder="Search item..." 
                        value="{{ request('search') }}"
                        class="pl-12 pr-4 py-2 bg-white border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-logos-gold w-64 text-sm"
                        oninput="debounceSearch()"> 
                </form>
                </div>

                <div class="flex items-center gap-2 overflow-x-auto pb-4 no-scrollbar">
                    @foreach($tabs as $tab)
                        <a href="{{ route('cashier.dashboard', ['category' => $tab, 'search' => request('search')]) }}" 
                        class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap
                        {{ (request('category', 'ALL') == $tab) 
                            ? 'bg-logos-blue text-white shadow-lg shadow-blue-100' 
                            : 'bg-white text-gray-400 hover:bg-gray-50' }}">
                            {{ $tab }}
                        </a>
                    @endforeach
                </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($inventory as $itemName => $sizes)
                @php
                    $name = strtolower($itemName);
                    $image = null;
                    $icon = 'fa-shirt'; 
                    $customSvg = null;

                    // --- PRIORITIZE IMAGES FOR SPECIFIC ITEMS ---
                    if (str_contains($name, 'peter pan')) {
                        $image = 'peter_pan.png';
                    } elseif (str_contains($name, 'sweater')) {
                        $image = 'sweater.png';
                    } elseif (str_contains($name, 'fleece') || str_contains($name, 'jacket')) {
                        // Updated to remove the space mismatch from your ls output
                        $image = 'fleece-hoodie.png'; 
                    } elseif (str_contains($name, 'track suit')) {
                        $image = 'Tracksuit.png';
                    } elseif (str_contains($name, 'trunks')) {
                        $image = 'swimming_trunk_boys.png';
                    } elseif (str_contains($name, 'swim suit') || str_contains($name, 'costume')) {
                        $image = 'ladies_swimsuit.png';
                    } elseif (str_contains($name, 'short')) {
                        $image = 'Games_shorts.png';
                    } elseif (str_contains($name, 'yellow pin stripped') || str_contains($name, 'sky blue')) {
                        // Specific image for the striped shirt
                        $image = 'Tie_collar.png';

                    // --- FALLBACK TO CUSTOM SVGS/ICONS FOR BASICS ---
                    } elseif (str_contains($name, 'trouser')) {
                        $customSvg = '<path d="M6 2h12l3 20h-7v-8h-4v8H3L6 2z" />';
                    } elseif (str_contains($name, 'skort') || str_contains($name, 'skirt')) {
                        $customSvg = '<path d="M8 2h8l5 20H3L8 2z" />';
                    } elseif (str_contains($name, 'tie')) {
                        $customSvg = '<path d="M6 2l4 4-3 11 5 5 5-5-3-11 4-4H6z" />';
                    } elseif (str_contains($name, 'blazer')) {
                        $icon = 'fa-user-tie';
                    } elseif (str_contains($name, 'socks') || str_contains($name, 'stocking')) {
                        $icon = 'fa-socks';
                    }
                @endphp
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-logos-blue overflow-hidden">
                            @if($image)
                                {{-- Display Image if it exists in the uniforms folder --}}
                                <img src="{{ asset('images/uniforms/' . $image) }}" 
                                    alt="{{ $itemName }}" 
                                    class="w-10 h-10 object-contain">
                            @elseif($customSvg)
                                {{-- Fallback to Custom SVG silhouettes --}}
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                                    {!! $customSvg !!}
                                </svg>
                            @else
                                {{-- Fallback to FontAwesome Icons --}}
                                <i class="fas {{ $icon }} text-xl"></i>
                            @endif
                        </div>
                                <span class="text-[10px] font-bold px-2 py-1 bg-blue-50 text-blue-600 rounded-full uppercase">
                                    {{ $sizes->first()->category }}
                                </span>
                    </div>
                    
                    <h3 class="font-bold text-logos-blue text-sm mb-4 h-10 overflow-hidden leading-tight">
                        {{ $itemName }}
                    </h3>

                    <div class="mt-4">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Select Size</label>
                        <select id="select-{{ Str::slug($itemName) }}" 
                            class="w-full bg-slate-50 border-none rounded-xl text-xs font-bold text-logos-blue focus:ring-2 focus:ring-logos-gold">
                            @foreach($sizes as $item)
                                <option value="{{ $item->id }}" data-size="{{ $item->size_label }}" data-price="{{ $item->price }}">
                                    {{ $item->size_label }} — KES {{ number_format($item->price) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button @click="addToCartFromDropdown('{{ Str::slug($itemName) }}', '{{ $itemName }}')" 
                        class="mt-4 w-full py-3 bg-logos-blue text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-logos-gold hover:text-logos-blue transition-all">
                        Add to Order
                    </button>
                </div>
                @empty
            <div class="col-span-full py-12 text-center text-gray-400">
                    <i class="fas fa-box-open text-4xl mb-4 opacity-20"></i>
                    <p>No items found matching your search.</p>
                </div>
            @endforelse
        </div>

    </div>
    

        <div class="w-full lg:w-80">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 sticky top-8 overflow-hidden">
                <div class="bg-logos-blue p-6 text-white text-center">
                    <i class="fas fa-shopping-cart mb-2 text-2xl text-logos-gold"></i>
                    <h3 class="font-bold uppercase tracking-widest text-sm">Current Order</h3>
                </div>
                
                <div class="space-y-4 max-h-[400px] overflow-y-auto">
                    <template x-if="cart.length === 0">
                        <div class="text-center text-gray-400 py-10">
                            <p class="text-xs italic">Cart is empty.<br>Click items to add.</p>
                        </div>
                    </template>

                    <template x-for="item in cart" :key="item.id">
                        <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100 mb-3">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-logos-blue uppercase leading-tight" x-text="item.name"></span>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase" x-text="'Size: ' + item.size"></span>
                                </div>
                                <button @click="removeFromCart(item.id)" class="text-red-400 hover:text-red-600">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center bg-white rounded-lg border border-gray-200 px-2 py-1 gap-3">
                                    <button @click="updateQty(item.id, -1)" class="text-gray-400 hover:text-logos-blue font-bold">-</button>
                                    <span class="text-xs font-black text-logos-blue" x-text="item.qty"></span>
                                    <button @click="updateQty(item.id, 1)" class="text-gray-400 hover:text-logos-blue font-bold">+</button>
                                </div>
                                <span class="text-xs font-black text-logos-blue" x-text="'K' + (item.price * item.qty).toLocaleString()"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="border-t border-dashed border-gray-200 pt-6 mt-6">
                    <div class="flex justify-between items-end mb-6">
                        <span class="text-xs font-bold text-gray-400 uppercase">Total Amount</span>
                        <span class="text-3xl font-black text-logos-blue" x-text="'KES ' + total.toLocaleString()"></span>
                    </div>
                    
                    <form action="{{ route('cashier.payment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cart_data" :value="JSON.stringify(cart)">
                        <button type="submit" x-show="cart.length > 0" 
                            class="w-full py-4 bg-logos-gold text-logos-blue font-black rounded-2xl shadow-lg hover:scale-105 transition uppercase tracking-widest text-xs">
                            Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @if(session('success'))
        <div id="toast" class="fixed bottom-10 right-10 z-50 animate-bounce">
            <div class="bg-logos-blue text-white px-8 py-4 rounded-3xl shadow-2xl border-4 border-logos-gold flex items-center gap-4">
                <div class="bg-logos-gold text-logos-blue rounded-full p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-black uppercase tracking-widest">Success!</p>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif


    <script>
        let timeout = null;
        function debounceSearch(){
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                document.getElementById('searchForm').submit();
            }, 500);
        }

        window.onload = function(){
            const input = document.getElementById('searchInput');
            if(input && input.value !== "") {
                input.focus();
                const val = input.value;
                input.value = "";
                input.value = val;
            }
        }
        
        function cartSystem() {
            return {
                cart: [],
                // This runs automatically when Alpine starts
                init() {
                    const storedCart = localStorage.getItem('logos_cart');
                    if (storedCart) {
                        this.cart = JSON.parse(storedCart);
                    }
                },
                persist() {
                    localStorage.setItem('logos_cart', JSON.stringify(this.cart));
                },
                addToCartFromDropdown(slug, itemName) {
                    const select = document.getElementById('select-' + slug);
                    const selectedOption = select.options[select.selectedIndex];
                    
                    const product = {
                        id: parseInt(selectedOption.value),
                        name: itemName,
                        size: selectedOption.getAttribute('data-size'),
                        price: parseFloat(selectedOption.getAttribute('data-price'))
                    };

                    let found = this.cart.find(i => i.id === product.id);
                    if (found) {
                        found.qty++;
                    } else {
                        this.cart.push({ ...product, qty: 1 });
                    }
                    this.persist();
                },
                updateQty(id, amount) {
                    let item = this.cart.find(i => i.id === id);
                    if (item) {
                        item.qty += amount;
                        if (item.qty <= 0) this.removeFromCart(id);
                    }
                    this.persist();
                },
                removeFromCart(id) {
                    this.cart = this.cart.filter(i => i.id !== id);
                    this.persist();
                },
                get total() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                }
            }
        }
        
        if(document.getElementById('toast')) {
            setTimeout(() => {
                document.getElementById('toast').style.display = 'none';
            }, 4000);
        }
    </script>

    @if(session('success'))
        <script>
            // Force clear the local storage so the next customer starts fresh
            localStorage.removeItem('logos_cart');
        </script>
    @endif

@endsection