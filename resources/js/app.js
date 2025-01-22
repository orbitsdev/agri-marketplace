import './bootstrap';


import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
// import axios from 'axios';

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: false,
//     auth: {
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//         },
//     },
// });

// // Fetch authenticated user's ID dynamically
// axios.get('/api/user').then(response => {
//     let userId = response.data.id;

//     // Listen for events on the user's private channel
//     window.Echo.private(`private-participant.${userId}`)
//         .listen('.Namu\\WireChat\\Events\\NotifyParticipant', (e) => {
//             console.log('Notification for participant:', e.message);
//             // Add your custom logic to handle the notification
//         });

//     // Example: Listen for conversation events
//     let conversationId = 1; // Replace with the actual conversation ID
//     window.Echo.private(`private-conversation.${conversationId}`)
//         .listen('.Namu\\WireChat\\Events\\MessageCreated', (e) => {
//             console.log('New message received:', e.message);
//             // Add your custom logic to handle the message
//         });
// }).catch(error => {
//     console.error('Error fetching user ID:', error);
// });




// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// // window.Echo = new Echo({
// //     broadcaster: 'pusher',
// //     key: import.meta.env.VITE_PUSHER_APP_KEY,
// //     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
// //     forceTLS: false
// // });

// TESTINF PURPSIE
// let userId = 5;

// window.Echo.private(`test.${userId}`)
//     .listen("TestEvent", (e) => {
//         console.log(e.message);
//     });
