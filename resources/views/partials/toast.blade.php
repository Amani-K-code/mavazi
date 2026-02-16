<div x-data="{ show: false, message: '', type: '' }"
     x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 5000)"
     class="fixed top-5 right-5 z-[100] w-80">
    
    <template x-if="show">
        <div :class="{
                'bg-red-600': type === 'LOW_STOCK',
                'bg-logos-blue': type === 'SYSTEM_NOTE',
                'bg-green-600': type === 'success'
             }" 
             class="text-white p-4 rounded-2xl shadow-2xl flex items-start gap-3 animate-bounce-in">
            <div class="bg-white/20 p-2 rounded-lg">
                <i class="fas" :class="type === 'LOW_STOCK' ? 'fa-exclamation-triangle' : 'fa-comment-alt'"></i>
            </div>
            <div class="flex-1">
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70" x-text="type.replace('_', ' ')"></p>
                <p class="text-xs font-bold" x-text="message"></p>
            </div>
            <button @click="show = false" class="opacity-50 hover:opacity-100">&times;</button>
        </div>
    </template>
</div>