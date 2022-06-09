const express = require('express');
const app = express();
const http = require('http');
const server = app.listen(3000, () => {
    console.log("Server is started!", 3000);
});

const io = require("socket.io")(server, {
    cors: {
        origin: "*"
    }
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
        console.log("user_connected", user_id, users[user_id]);
    });
    // on join room
    socket.on('join_room', (data) => {
        console.log("111111111111111111111111", data, `${data.roomName}${data.data.order_id}`);
        // socket.join(data.channelName);

        console.log("rooooooooooooom", socket.rooms);
        // if (users[data.data.user_id] == socket.id) { // here we should make the auth
            // socket.join(channelName + '1');
            console.log("room name = ", data.roomName);
            if (data.roomName == 'add_order_room') {
                console.log("add_order_room==========", data);
                socket.join(data.roomName);
                io.to(data.roomName).emit("listen_to_add_order_room", data);
            } else {
                socket.join(`${data.roomName}${data.data.order_id}`);
                let data1 = {
                    "long": data.data.long,
                    "lat": data.data.lat
                };

                io.to(`${data.roomName}${data.data.order_id}`).emit("position" + `${data.data.order_id}`, data1);
            }

        // }
        console.log("room that first user in is ===> ", socket.rooms);

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




function randomInRange(min, max) {
    return Math.random() < 0.5 ? ((1 - Math.random()) * (max - min) + min) : (Math.random() * (max - min) + min);
}