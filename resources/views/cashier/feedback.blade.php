@extends('layouts.app')

@section('content')
        <div class="max-w-2xl mx-auto py-20 text-center">
            <div class="bg-white p-12 rounded-[3rem] shadow-xl border border-gray-100">
                <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check text-3xl"></i>
                </div>
                
                <h2 class="text-3xl font-black text-logos-blue uppercase">Transaction Success!</h2>
                <p class="text-gray-500 font-medium mt-2">Receipt No: <span class="text-logos-blue font-bold">{{ $sale->receipt_no }}</span></p>

                <div class="mt-10 p-6 bg-slate-50 rounded-3xl text-left border border-dashed border-gray-200">
                    <h4 class="text-[10px] font-black uppercase text-gray-400 mb-4 tracking-[0.2em] text-center">Customer Feedback</h4>
                    
                    <form id="feedbackForm" action="{{ route('feedback.store', $sale->id) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label class="block text-center text-xs font-bold mb-2 text-gray-400 uppercase tracking-widest">Service Rating</label>
                            <div class="flex gap-4 justify-center py-6">
                                @for($i=1; $i<=5; $i++)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="rating" value="{{ $i }}" class="peer absolute inset-0 opacity-0 z-10 cursor-pointer" required>
                                    
                                    <div class="w-14 h-14 rounded-2xl bg-white border-2 border-slate-100 flex items-center justify-center text-xl font-black text-slate-400 transition-all duration-200 shadow-sm peer-checked:bg-[#002b5c] peer-checked:text-white peer-checked:border-[#002b5c] peer-hover:border-[#002b5c] group-hover:scale-110">
                                        {{ $i }}
                                    </div>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <textarea name="comments" placeholder="Additional comments (Optional)..." class="w-full p-4 bg-white rounded-2xl border-none focus:ring-2 focus:ring-logos-gold text-sm h-24 shadow-sm"></textarea>
                        </div>

                        <div class="flex flex-col gap-4">
                            <button type="submit" class="w-full py-5 bg-logos-blue text-white font-black rounded-2xl shadow-xl uppercase tracking-widest text-sm hover:brightness-110 transition-all">
                                Submit Feedback & Download Receipt
                            </button>
                            
                            <a href="{{ route('sales.download', $sale->id) }}" class="text-center text-gray-400 font-bold text-xs uppercase hover:text-logos-blue transition">
                                Skip Feedback, Just Download Receipt
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('feedbackForm').addEventListener('submit', function() {
                // This opens the PDF in a new tab while the form submits in this tab
                window.open("{{ route('sales.download', $sale->id) }}", "_blank");
            });
        </script>
@endsection