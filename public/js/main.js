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
        return token !== null;
    };

    Message.prototype.getToken = function() {
        return token;
    };

    Message.prototype.getExtraData = function() {
        return extraData;
    };

    return Message;
}());

AerysPlayground.Output = (function() {
    'use strict';

    var formatter;
    var output;
    var form;

    function Output(_formatter) {
        formatter = _formatter
        output    = document.querySelector('.content');
        form      = document.querySelector('form');
    }

    Output.prototype.write = function(message) {
        var line = document.createElement('p');

        line.appendChild(formatter.format(message.getMessage()));

        output.insertBefore(line, form);

        document.body.scrollTop = document.body.scrollHeight;
    };

    return Output;
}());

/**
 * Thanks to all my friends in room 17 <3
 */
AerysPlayground.Formatter = (function() {
    'use strict';

    const colorRegexp = /#(?:[0-9a-fA-F]{3}){1,2}/;

    function Formatter() {

    }

    Formatter.prototype.format = function(inputString) {
        let outputNode = document.createElement('span');
        let part, nextIndex, newNode, color, tempNode, currNode = outputNode;
        do{
            nextIndex = inputString.search(colorRegexp);
            console.log(nextIndex);
            nextIndex = nextIndex !== -1 ? nextIndex : inputString.length;
            part = inputString.substring(0, nextIndex);
            inputString = inputString.substr(nextIndex);
            currNode.textContent = part;
            color = inputString.match(colorRegexp);
            color = color ? color[0] : '';
            if( ! color ){
                continue;
            }
            inputString = inputString.substr(color.length);
            tempNode = document.createElement('span');
            tempNode.style.color = color;
            currNode.appendChild(tempNode);
            currNode = tempNode;
        }while(inputString.length);

        return outputNode;
    };

    return Formatter;
}());

(function () {
    'use strict';

    var output      = new AerysPlayground.Output(new AerysPlayground.Formatter());
    var input       = document.querySelector('input');
    var connection  = new WebSocket('ws://localhost:8081/ws');
    var token       = null;
    var lastMessage = null;

    var lastCommands = [];

    connection.addEventListener('message', function(data) {
        lastMessage = new AerysPlayground.Message(data.data);

        if (lastMessage.hasToken()) {
            token = lastMessage.getToken();
            console.log(token);
        }

        output.write(lastMessage);

        if (lastMessage.getExtraData().hasOwnProperty('nextPrefix')
            && (
                lastMessage.getExtraData()['nextPrefix'].indexOf('register3 ') === 0
                || lastMessage.getExtraData()['nextPrefix'].indexOf('register4 ') === 0
                || lastMessage.getExtraData()['nextPrefix'].indexOf('join3 ') === 0
            )
        ) {
            input.setAttribute('type', 'password');
        } else {
            input.setAttribute('type', 'text');
        }
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (token === null) {
            return;
        }

        var prefix = '';

        if (lastMessage.getExtraData().hasOwnProperty('nextPrefix')) {
            prefix = lastMessage.getExtraData()['nextPrefix'];
        }

        lastCommands.push(input.value);

        connection.send(JSON.stringify({
            token: token,
            content: prefix + input.value,
            extraData: lastMessage.getExtraData()
        }));

        input.value = '';
    });
}());
