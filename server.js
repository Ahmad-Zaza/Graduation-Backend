const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = new Server(server, {
    cors: "*"
});
var Redis = require('ioredis');
var redis = new Redis();
var users = [];

redis.subscribe('private-channel', function() {
    console.log('subscribed to private channel ===');

});

redis.on('message', function(channel, message) {
    message = JSON.parse(message);
    console.log("zaza channel", channel);
    console.log("zaza msg", message);
    if (channel == 'private-channel') {
        message.data.data = "hellow my friend";
        let data = message.data.data;
        let receiver_id = 1;
        let event = message.event;
        console.log("zaza event", data, event);
        io.to(`${users[receiver_id]}`).emit(channel + ':' + message.event, data);
        // console.log("io====", io);
    }
});


io.on('connection', (socket) => {
    console.log('connection11');

    socket.on('user_connected', (user_id) => {
        users[user_id] = socket.id;
        console.log("user_connected1", user_id, users[user_id]);

    })

    socket.on('sendChatToServer', (message) => {
        console.log(message);

        // io.sockets.emit('sendChatToClient', message);
        socket.broadcast.emit('sendChatToClient', message);
    });


    socket.on('disconnect', (socket) => {
        console.log('Disconnect');
    });
});

server.listen(3000, () => {
    console.log("server is running111");
});
