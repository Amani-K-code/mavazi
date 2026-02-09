@extends('layouts.app')

@section('content')
        <div class="max-w-4xl mx-auto py-12">
            <div class="bg-white rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden">
                <div class="bg-logos-blue p-8 text-white flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-black uppercase tracking-tight">Checkout & Payment</h2>
                        <p class="text-logos-gold text-xs font-bold uppercase tracking-widest mt-1">Finalize Order Details</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] uppercase opacity-60">Total to Pay</span>
                        <span class="text-3xl font-black tracking-tighter">KES {{ number_format($total) }}</span>
                    </div>
                </div>


                <div class="p-10 bg-slate-50 border-b border-dashed border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-8">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto" alt="LCS Logo">
                            <div>
                                <h3 class="text-lg font-black text-logos-blue leading-tight uppercase">Logos Christian School</h3>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Uniform Mavazi Shop | Receipt Draft</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase">Date</p>
                            <p class="text-sm font-bold text-logos-blue">{{ now()->format('d M, Y') }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="py-3 text-[10px] font-black uppercase text-gray-400">Description</th>
                                    <th class="py-3 text-[10px] font-black uppercase text-gray-400 text-center">Qty</th>
                                    <th class="py-3 text-[10px] font-black uppercase text-gray-400 text-right">Unit Price</th>
                                    <th class="py-3 text-[10px] font-black uppercase text-gray-400 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($cartData as $item)
                                <tr>
                                    <td class="py-4">
                                        <p class="text-sm font-bold text-logos-blue leading-tight">{{ $item['name'] }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Size: {{ $item['size'] }}</p>
                                    </td>
                                    <td class="py-4 text-center text-sm font-black text-gray-600">{{ $item['qty'] }}</td>
                                    <td class="py-4 text-right text-sm font-bold text-gray-600">KES {{ number_format($item['price']) }}</td>
                                    <td class="py-4 text-right text-sm font-black text-logos-blue">KES {{ number_format($item['price'] * $item['qty']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-logos-blue">
                                    <td colspan="3" class="py-4 text-right text-xs font-black uppercase text-gray-400">Total Amount Due</td>
                                    <td class="py-4 text-right text-xl font-black text-logos-blue">KES {{ number_format($total) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <form action="{{ route('sales.store') }}" method="POST" class="p-10 space-y-8">
                    @csrf
                    <input type="hidden" name="cart_data" value="{{ json_encode($cartData) }}">
                    <input type="hidden" name="total_amount" value="{{ $total }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b pb-2">Customer Info</h3>
                            <div>
                                <label class="block text-[10px] font-bold uppercase ml-2 mb-1 text-gray-500">Parent Name (Required)</label>
                                <input type="text" name="customer_name" required class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-logos-gold font-medium">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase ml-2 mb-1 text-gray-500">Child's Name (Required)</label>
                                <input type="text" name="child_name" required class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-logos-gold font-medium">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b pb-2">Transaction Details</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase ml-2 mb-1 text-gray-500">Mode</label>
                                    <select name="payment_method" required class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-logos-gold text-sm font-bold">
                                        <option value="M-PESA">M-PESA</option>
                                        <option value="PDQ">PDQ (Card)</option>
                                        <option value="PRE-PAID">Pre-Paid</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase ml-2 mb-1 text-gray-500">Status</label>
                                    <select name="status" id="statusSelect" class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-logos-gold text-sm font-bold">
                                        <option value="CONFIRMED">CONFIRMED</option>
                                        <option value="PENDING">PENDING</option>
                                        <option value="BOOKED">BOOKED</option>
                                    </select>
                                </div>
                            </div>
                            <div id="referenceGroup">
                                <label class="block text-[10px] font-bold uppercase ml-2 mb-1 text-gray-500">Reference ID (Required)</label>
                                <input type="text" 
                                    name="reference_id" 
                                    id="referenceInput"
                                    required 
                                    placeholder="UAD4GTY9IO80" 
                                    class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-logos-gold font-mono font-bold uppercase">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t flex flex-col md:flex-row items-center justify-between gap-6">
                        <a href="{{ route('cashier.dashboard') }}" class="text-gray-400 font-bold text-xs uppercase hover:text-logos-blue transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Catalog
                        </a>
                        <button type="submit" class="w-full md:w-auto px-12 py-5 bg-logos-blue text-white font-black rounded-2xl shadow-xl hover:brightness-125 transition-all uppercase tracking-widest text-xs">
                            Complete Transaction & Print Receipt
                        </button>
                    </div>
                </form>
            </div>


            <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const statusSelect = document.getElementById('statusSelect');
                        const referenceGroup = document.getElementById('referenceGroup');
                        const referenceInput = document.getElementById('referenceInput');

                        function toggleReferenceField() {
                            if (statusSelect.value === 'BOOKED') {
                                // Hide and remove requirement for BOOKED
                                referenceGroup.style.display = 'none';
                                referenceInput.removeAttribute('required');
                                referenceInput.value = ''; // Clear it so it doesn't send old data
                            } else {
                                // Show and require for CONFIRMED/PENDING
                                referenceGroup.style.display = 'block';
                                referenceInput.setAttribute('required', 'required');
                            }
                        }

                        // Run on page load and whenever the status changes
                        statusSelect.addEventListener('change', toggleReferenceField);
                        toggleReferenceField(); 
                });
            </script>
        </div>
@endsection