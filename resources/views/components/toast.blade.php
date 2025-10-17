@props(['type' => 'success', 'message' => ''])
@php
  $colorClass = $type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 
                ($type === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 
                'bg-gray-50 border-gray-200 text-gray-800');
@endphp
<div id="toast" class="mb-4 rounded-xl border {{ $colorClass }} px-4 py-3">
  {{ $message }}
</div>
<script>
setTimeout(() => {
  const t = document.getElementById('toast');
  if (t) {
    t.style.opacity = '0';
    t.style.transition = 'opacity 0.4s';
    setTimeout(() => t.remove(), 400);
  }
}, 2200);
</script>
