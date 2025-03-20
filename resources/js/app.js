import './bootstrap'; // Load Bootstrap setup

import Echo from 'laravel-echo'; // Import Laravel Echo
import Pusher from 'pusher-js'; // Import Pusher


window.Pusher = Pusher;


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY, // Get the key from environment variables
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // Get the cluster from environment variables
    forceTLS: true, // Use HTTPS
});


