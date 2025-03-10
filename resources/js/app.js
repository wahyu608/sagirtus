import './bootstrap';
import Alpine from 'alpinejs';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

window.Alpine = Alpine;
Alpine.start();

// Pastikan selalu dalam mode light
document.documentElement.classList.remove("dark");

// Hapus preferensi tema dari localStorage (opsional)
localStorage.removeItem("color-theme");
