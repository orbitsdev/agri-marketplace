import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY || "pusherkey", // Fallback key
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || "mt1", // ✅ Fix missing cluster
    wsHost: import.meta.env.VITE_PUSHER_HOST || "agrimarket.store",
    wsPort: import.meta.env.VITE_PUSHER_PORT || 6001,
    forceTLS: false, // ✅ Required for HTTPS
    encrypted: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

console.log("✅ WebSocket Setup Completed");
