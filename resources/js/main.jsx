import React from 'react';
import { createRoot } from 'react-dom/client';
import Hero from './components/Hero';
import WhatsAppFab from './components/WhatsAppFab';

console.log('main.jsx loaded');

// Mount Hero
const heroEl = document.getElementById('welcome-hero');
if (heroEl) {
  console.log('Found welcome-hero mount point');
  try {
    const props = heroEl.dataset.props ? JSON.parse(heroEl.dataset.props) : {};
    const root = createRoot(heroEl);
    root.render(<Hero {...props} />);
    console.log('Hero mounted');
  } catch (e) {
    console.error('Hero mount failed', e);
    // Fallback: render minimal content so user sees something
    heroEl.innerHTML = `
      <div class="w-full h-80 md:h-96 rounded-2xl overflow-hidden bg-gray-900 flex items-center justify-center">
        <div class="text-center text-white px-8">
          <h3 class="text-3xl md:text-4xl font-bold mb-4">Selamat Datang di Alwi College</h3>
          <p class="text-lg md:text-xl">Membangun Masa Depan Cerah Melalui Pendidikan Berkualitas</p>
        </div>
      </div>
    `;
  }
} else {
  console.warn('welcome-hero mount point not found');
}

// Mount WhatsApp FAB
const fabEl = document.getElementById('whatsapp-fab');
if (fabEl) {
  console.log('Found whatsapp-fab mount point');
  try {
    const rootFab = createRoot(fabEl);
    rootFab.render(<WhatsAppFab phone="6282179970473" />);
    console.log('WhatsAppFab mounted');
  } catch (e) {
    console.error('WhatsAppFab mount failed', e);
    // Fallback simple link
    fabEl.innerHTML = `<div class="fixed bottom-6 right-6 z-50"><a href="https://wa.me/6282179970473" target="_blank" rel="noopener noreferrer" class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center">WA</a></div>`;
  }
} else {
  console.warn('whatsapp-fab mount point not found');
}
