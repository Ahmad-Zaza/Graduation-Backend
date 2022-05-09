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
    // console.log('subscribed to private channel ===');

});

// console.log("redis=====", redis.on('ff', function(channel, ff) {}))
redis.on('message', function(channel, ff) {
    ff = JSON.parse(ff);
    // console.log("zaza channel", channel);
    // console.log("zaza msg", ff);
    if (channel == 'private-channel') {
        ff.data.data = "hellow my friend";
        let data = ff.data.data;
        let receiver_id = 1;
        let event = ff.event;
        // console.log("zaza event", data, event);
        // io.to(`${users[receiver_id]}`).emit(channel + ':' + ff.event, data);
        io.to("private-channel").emit(channel + ':' + ff.event, data); // listen to room
        // console.log("io====", io);
    }
});


io.on('connection', (socket) => {
    // console.log('connection11');
    // console.log("redis", redis);
    socket.on('user_connected', (user_id) => {
        users[user_id] = socket.id;
        console.log("user_connected1", user_id, users[user_id]);

    });

    socket.on('join_room', (channelName, user_ids) => {
        console.log("111111111111111111111111", user_ids, socket.id);
        if (users[user_id1] == socket.id) { // here we should make the auth
            socket.join(channelName);
            // console.log("room that first user in is ===> ", socket.rooms);
        }
    });

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