// import './bootstrap'; // Load Bootstrap setup

// import Echo from 'laravel-echo'; // Import Laravel Echo
// import Pusher from 'pusher-js'; // Import Pusher

// // Attach Pusher to the global window object
// window.Pusher = Pusher;

// // Configure Laravel Echo
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY, // Get the key from environment variables
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // Get the cluster from environment variables
//     forceTLS: true, // Use HTTPS
// });
import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    authEndpoint: '/broadcasting/auth', // Default auth endpoint for Laravel
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }
});
