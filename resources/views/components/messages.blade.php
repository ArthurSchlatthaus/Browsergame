<script>
    let messagesGlobal = null
    let receivedMessages = null
    let sendMessages = null

    window.initMessages = function initMessages() {
        messagesGlobal = window.getGlobalMessages()
        if (messagesGlobal != null) {
            receivedMessages = messagesGlobal.receivedMessages
            sendMessages = messagesGlobal.sendMessages

            document.getElementById("sendMessage").innerHTML = ""
            let sendMessageTitle = document.createElement("h4")
            sendMessageTitle.innerText = "Send Messages"
            document.getElementById("sendMessage").appendChild(sendMessageTitle)

            document.getElementById("receivedMessage").innerHTML = ""
            let receivedMessageTitle = document.createElement("h4")
            receivedMessageTitle.innerText = "Received Messages"
            document.getElementById("receivedMessage").appendChild(receivedMessageTitle)

            receivedMessages.forEach((receivedMessage) => {
                let container = document.createElement("div")
                container.className = "card "
                let header = document.createElement("div")
                header.className = "card-header"
                let title = document.createElement("h5")
                title.className = "card-title"
                title.innerText = 'Message from ' + receivedMessage.senderName
                let subtitle = document.createElement("h5")
                subtitle.className = "card-subtitle mb-2"
                subtitle.innerText = formatDate(new Date(receivedMessage.created_at))
                if (receivedMessage.wasRead === 0) {
                    let unread = document.createElement("a")
                    unread.innerText = "- unread"
                    subtitle.appendChild(unread)
                }
                let body = document.createElement("div")
                body.className = "card-body"
                let textarea = document.createElement("textarea")
                textarea.className = "card-text form-control"
                textarea.readOnly = "true"
                textarea.type = "text"
                textarea.cols = 22
                textarea.rows = 10
                textarea.innerHTML = receivedMessage.message
                let readBtn = document.createElement("a")
                if (receivedMessage.wasRead === 0) {
                    readBtn = document.createElement("button")
                    readBtn.className = "btn btn-dark m-2"
                    readBtn.innerText = "mark as read"
                    readBtn.onclick = function () {
                        sendRead(receivedMessage.id)
                    }
                }
                let answerBtn = document.createElement("button")
                answerBtn.className = "btn btn-dark m-2"
                answerBtn.innerText = "answer"
                answerBtn.onclick = function () {
                    document.getElementById('receiver').value = receivedMessage.senderName
                    document.getElementById('message').focus()
                }

                let deleteBtn = document.createElement("button")
                deleteBtn.className = "btn btn-dark btn-outline-danger m-2"
                deleteBtn.innerText = "delete"
                deleteBtn.onclick = function () {
                    sendDelete(receivedMessage.id)
                }
                body.appendChild(textarea)
                body.appendChild(readBtn)
                body.appendChild(answerBtn)
                body.appendChild(deleteBtn)
                header.appendChild(title)
                header.appendChild(subtitle)
                container.appendChild(header)
                container.appendChild(body)
                document.getElementById("receivedMessage").appendChild(container)
            })
            sendMessages.forEach((sendMessage) => {
                let container = document.createElement("div")
                container.className = "card "
                let header = document.createElement("div")
                header.className = "card-header"
                let title = document.createElement("h5")
                title.className = "card-title"
                title.innerText = 'Message to ' + sendMessage.receiverName
                let subtitle = document.createElement("h5")
                subtitle.className = "card-subtitle mb-2"
                subtitle.innerText = formatDate(new Date(sendMessage.created_at))
                if (sendMessage.wasRead === 0) {
                    let unread = document.createElement("a")
                    unread.innerText = "- unread"
                    subtitle.appendChild(unread)
                }
                let body = document.createElement("div")
                body.className = "card-body"
                let textarea = document.createElement("textarea")
                textarea.className = "card-text form-control"
                textarea.readOnly = "true"
                textarea.type = "text"
                textarea.cols = 22
                textarea.rows = 10
                textarea.innerHTML = sendMessage.message

                let sendBtn = document.createElement("button")
                sendBtn.className = "btn btn-dark btn-outline-danger m-2"
                sendBtn.innerText = "delete"
                sendBtn.onclick = function () {
                    sendDelete(sendMessage.id)
                }
                body.appendChild(textarea)
                body.appendChild(sendBtn)
                header.appendChild(title)
                header.appendChild(subtitle)
                container.appendChild(header)
                container.appendChild(body)
                document.getElementById("sendMessage").appendChild(container)
            })
        }
    }

    function answer(name) {
        this.receiver = name
        document.getElementById("message").focus()
    }

    function sendRead(message_id) {
        $.ajax({
            url: "/sendRead",
            method: 'POST',
            data: {
                message_id: message_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                window.setGlobalPlayer(result.player)
                window.setGlobalMessages(result.messages)
                window.initMessages()
                window.setPlayerStatusBar()
            }
        });
    }

    function sendDelete(message_id) {
        $.ajax({
            url: "/sendDelete",
            method: 'POST',
            data: {
                message_id: message_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                window.setGlobalPlayer(result.player)
                window.setGlobalMessages(result.messages)
                window.initMessages()
                window.setPlayerStatusBar()
            }
        });
    }

    function sendMessage(messageArray) {
        $.ajax({
            url: "/sendMessage",
            method: 'POST',
            data: {
                messageArray: messageArray,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                window.setGlobalPlayer(result.player)
                window.setGlobalMessages(result.messages)
                window.initMessages()
                document.getElementById("receiver").value = ""
                document.getElementById("message").value = ""
                window.setPlayerStatusBar()
            }
        });
    }

    function reloadMessages(){
        $.ajax({
            url: "/reloadMessage",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                window.setGlobalPlayer(result.player)
                window.setGlobalMessages(result.messages)
                window.initMessages()
                document.getElementById("receiver").value = ""
                document.getElementById("message").value = ""
                window.setPlayerStatusBar()
            }
        });
    }

    function padTo2Digits(num) {
        return num.toString().padStart(2, '0');
    }

    function formatDate(date) {
        let day = [
            padTo2Digits(date.getDate()),
            padTo2Digits(date.getMonth() + 1),
            date.getFullYear().toString().substr(-2),
        ].join('.');
        return day + ' ' + padTo2Digits(date.getHours()) + ':' + padTo2Digits(date.getMinutes())
    }
</script>
<button class="btn btn-dark" onclick="reloadMessages()">Reload</button>
<div class="d-flex flex-row flex-wrap justify-content-center align-items-start">
    @if(auth()->user()->isAdmin())
        <div class="d-flex flex-column">
            <h4>Messages:</h4>
            @foreach(\App\Models\Messages::all() as $message)
                @if($message->senderName != 'Ludus2 Team')
                    <a>{{'Sender: '.$message->senderName.' Reciever: '.$message->recieverName.' Read: '.$message->wasRead.' Text: '.$message->message}}</a>
                    <br>
                @endif
            @endforeach
        </div>
    @else
        <div style="width: 30%!important;" class="p-2" id="receivedMessage">
        </div>
        <div style="width: 30%!important;" class="p-2">
            <h4>Write Message</h4>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        Message to
                        <label for="receiver"></label>
                        <input class="form-control" id="receiver" type="text" value="">
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <label>Text:</label>
                        <textarea id="message" class="form-control" type="text" cols="40" rows="5"></textarea>
                    </p>
                    <div>
                        <button class="btn btn-dark"
                                onclick="sendMessage(document.getElementById('receiver').value+'/split_/'+document.getElementById('message').value)">
                            send
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 30%!important;" class="p-2" id="sendMessage">
        </div>
    @endif
</div>
