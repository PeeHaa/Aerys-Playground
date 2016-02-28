var AerysPlayground = AerysPlayground || {};

AerysPlayground.Message = (function() {
    'use strict';

    var message;
    var token = null;
    var extraData = [];

    function Message(jsonMessage) {
        var parsedMessage = JSON.parse(jsonMessage);

        message = parsedMessage.message;
        extraData = parsedMessage.extraData;

        if (parsedMessage.hasOwnProperty('token')) {
            token = parsedMessage.token;
        }
    }

    Message.prototype.getMessage = function() {
        return message;
    };

    Message.prototype.hasToken = function() {
        return token;
    };

    Message.prototype.getToken = function() {
        return token !== null;
    };

    Message.prototype.getExtraData = function(elem) {
        return extraData[elem];
    };

    return Message;
}());

AerysPlayground.Output = (function() {
    'use strict';

    var output;
    var form;

    function Output() {
        output = document.querySelector('.content');
        form   = document.querySelector('form');
    }

    Output.prototype.write = function(message) {
        var line = document.createElement('p');

        line.appendChild(document.createTextNode(message.getMessage()));

        output.insertBefore(line, form);
    };

    return Output;
}());

(function () {
    'use strict';

    var output     = new AerysPlayground.Output();
    var input      = document.querySelector('input');
    var connection = new WebSocket('ws://localhost:8081/ws');
    var token      = null;

    connection.addEventListener('message', function(data) {
        var message = new AerysPlayground.Message(data.data);

        if (message.hasToken()) {
            token = message.getToken();
        }

        output.write(message);
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (token === null) {
            return;
        }

        connection.send(JSON.stringify({
            token: token,
            content: input.value
        }));

        input.value = '';
    });
}());
