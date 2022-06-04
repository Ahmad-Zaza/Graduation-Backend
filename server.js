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

redis.on('message', function(channel, ff) {
    ff = JSON.parse(ff);
    console.log("zaza channel", channel);
    console.log("zaza msg", ff);
    if (channel == 'private-channel') {
        console.log("inPrivateChannel", channel);
        ff.data.data = "hellow my friend";
        let data = ff.data.data;
        let receiver_id = 1;
        let event = ff.event;
        io.to(`${users[receiver_id]}`).emit(channel + ':' + ff.event, data);
        io.to("private-channel").emit(channel + ':' + ff.event, data); // listen to room
    }
});


io.on('connection', (socket) => {
    // on connection
    socket.on('user_connected', (user_id) => {
        users[user_id] = socket.id;
        console.log("user_connected1", user_id, users[user_id]);
    });
    // on join room
    socket.on('join_room', (channelName, user_id) => {
        console.log("111111111111111111111111", user_id, socket.id);
        if (users[user_id] == socket.id) { // here we should make the auth
            socket.join(channelName + '1');
            let data = {
                "long": randomInRange(1, 200),
                "lat": randomInRange(1, 200)
            };

            io.to(channelName + '1').emit("position", data);
            console.log("room that first user in is ===> ", socket.rooms);
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


function randomInRange(min, max) {
    return Math.random() < 0.5 ? ((1 - Math.random()) * (max - min) + min) : (Math.random() * (max - min) + min);
}