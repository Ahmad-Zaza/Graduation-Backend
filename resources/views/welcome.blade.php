<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Chat App Socket.io</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS only -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <style>
        .chat-row {
            margin: 50px;
        }

        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        ul li {
            padding: 8px;
            background: #928787;
            margin-bottom: 20px;
        }

        ul li:nth-child(2n-2) {
            background: #c3c5c5;
        }

        .chat-input {
            border: 1px soild lightgray;
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            padding: 8px 10px;
            color: #fff;
        }
    </style>
</head>

<body>
    <h1>user is </h1>{{ Auth::guard('company-api')->user() }}
    <div class="container">
        <div class="row chat-row">
            <div class="chat-content">
                <ul>

                </ul>
            </div>

            <div class="chat-section">
                <div class="chat-box">
                    <div class="chat-input bg-primary" id="chatInput" contenteditable="">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
    {{-- <script src="https://cdn.socket.io/2.4.1/socket.io.min.js"
        integrity="sha384-LzhRnpGmQP+lOvWruF/lgkcqD+WDVt9fU3H4BWmwP5u5LTmkUGafMcpZKNObVMLU" crossorigin="anonymous"> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
    </script>
    {{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}

    <script>
        $(function() {
            // connect socket
            let ip_address = 'localhost';
            let socket_port = '3000';
            let socket = io(ip_address + ':' + socket_port);

            /////
            let chatInput = $('#chatInput');
            chatInput.keypress(function(e) {
                let message = $(this).html();
                console.log(message);
                if (e.which === 13 && !e.shiftKey) {
                    socket.emit('sendChatToServer', message);
                    chatInput.html('');
                    return false;
                }
            });

            socket.on('connect', function() {
                socket.emit('user_connected', 1);
                var arr = [1, 2, 3, 4, 5];
                //socket.emit('join_room', "private-channel", 1);
            });

            /*socket.on("private-channel:App\\Events\\TestEvent", function(ff) {
                console.log("listented to privateeeeee", ff);

                alert(ff);
            });*/
            setInterval(function() {
                console.log("we work in time");
                //socket.emit('join_room', "order", 1);
                var data = {
                    "roomName": "order",
                    //"roomName": "add_order_room",
                    "data": {
                        "order_id": 1,
                        "user_id": 1,
                        "long": 3.3434,
                        "lat": 3.4343,
                        "status": 3
                    }
                };
                socket.emit('join_room', data);
            }, 2000);



            socket.on("position1", function(position) {
                console.log("listented to privateeeeee room", position);
                //alert('long => ' + position.long + '  lat => ' + position.lat);
            });

            socket.on("listen_to_add_order_room", function(data) {
                console.log("listented to privateeeeee room", data);
                //alert('long => ' + position.long + '  lat => ' + position.lat);
            });

            socket.on('sendChatToClient', (message) => {
                console.log("we listentd here==============");
                $('.chat-content ul').append(`<li>${message}</li>`);
            });



            //socket.on("order.1:App\\Events\\LiveOrderEvent",
            //    function(order) {
            //        console.log("listened to order private channel");
            //    });

        });
    </script>
</body>

</html>
