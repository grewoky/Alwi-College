@props(['type' => 'success', 'message' => ''])
@php
  $color = $type==='success' ? 'green' : ($type==='error' ? 'red' : 'gray');
@endphp
<div id="toast" class="mb-4 rounded-xl border border-{{ $color }}-200 bg-{{ $color }}-50 text-{{ $color }}-800 px-4 py-2">
  {{ $message }}
</div>
<script>setTimeout(()=>{const t=document.getElementById('toast'); if(t){ t.style.opacity='0'; t.style.transition='opacity .4s'; setTimeout(()=>t.remove(),400);}}, 2200);</script>
