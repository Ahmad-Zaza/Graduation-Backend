import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});

console.log("we are in app.js");
Echo.channel(`private-channel`)
    .listen('.private-channel', (e) => {
        consolec.log(e);
        console.log("we listen from echo");
    });
