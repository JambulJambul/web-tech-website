<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chat Room</title>
    <script src="https://kit.fontawesome.com/e391ce7786.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <style>
       #head-ribbon {
        background-color: black;
        text-align: center;
        height: 50px;
      }

      #chat-list {
        height: calc(100% - 50px);
        overflow-x: hidden;
        overflow-y: scroll;
      }

      #chatbox {
        height: 80%;
        overflow-y: scroll;
      }

      #messagebox {
        height: 20%;
        align-items: center;
      }
      html,
      body {
        height: 100%;
      }

      body {
        background-image: url('../img/wall.jpg');
      }

      .message-container {
        min-width: min-content;
        max-width: 40%;
      }

      .sender-message {
        background-color: beige;
      }

      .my-message {
        background-color: rgb(82, 143, 117);
      }
    </style>

    <script>
      var months = ["January", "February", "March", "April", "May", "June", "July", "August", "Septemeber", "October", "November", "December"];
      var chatData = [
        {
          sender: "client",
          message:
            "Hi!",
          time: "2022-05-10T15:15:00+07:00",
        },
        {
          sender: "remote",
          message:
            "Hi!",
          time: "2022-05-10T15:15:30+07:00",
        },
      ];

      $(document).ready(function () {
        $("#json-upload").change(loadFromJSON);
      });

      async function loadFromJSON(event) {
        const file = event.target.files;

        await file[0].text().then((value) => {
          chatData = JSON.parse(value);
        });

        $("#chatbox").html("");
        console.log(chatData);
        chatData.forEach((value, index, arr) => {
          console.log(value);
          let prefix = '<div class="d-flex flex-row-reverse mb-4"><div class="message-container"><div class="my-message px-2 py-1 border border-dark rounded-1">';
          let suffix = "</div>";
          if (value.sender == "remote") {
            prefix = '<div class="d-flex mb-4"><div class="message-container"><div class="sender-message px-2 py-1 border border-dark rounded-1">';
          }

          let time = new Date(value.time);
          let timeText = "<small>" + time.getDate() + " " + months[time.getMonth()] + ", " + time.getHours() + ":" + time.getMinutes() + "<small>";
          $("#chatbox").append(prefix + value.message + suffix + timeText + "</div></div>");
        });
      }

      function sendMessage() {
        let prefix = '<div class="d-flex flex-row-reverse mb-4"><div class="message-container"><div class="my-message px-2 py-1 border border-dark rounded-1">';
        let suffix = "</div>";
        let time = new Date();
        let ISOTime =
          time.getUTCFullYear() + "-" + appendZero(time.getUTCMonth()) + "-" + appendZero(time.getUTCDate()) + "T" + appendZero(time.getUTCHours()) + ":" + appendZero(time.getUTCMinutes()) + ":" + appendZero(time.getUTCSeconds()) + "Z";
        let timeText = "<small>" + time.getDate() + " " + months[time.getMonth()] + ", " + time.getHours() + ":" + time.getMinutes() + "<small>";
        let messageValue = $("textarea").val();

        $("#chatbox").append(prefix + messageValue + suffix + timeText + "</div></div>");
        $("textarea").val("");
        chatData.push({ sender: "client", message: messageValue, time: ISOTime });

        let prefix2 = '<div class="d-flex mb-4"><div class="message-container"><div class="sender-message px-2 py-1 border border-dark rounded-1">';
        let suffix2 = "</div>";
        time = new Date();
        ISOTime =
          time.getUTCFullYear() + "-" + appendZero(time.getUTCMonth()) + "-" + appendZero(time.getUTCDate()) + "T" + appendZero(time.getUTCHours()) + ":" + appendZero(time.getUTCMinutes()) + ":" + appendZero(time.getUTCSeconds()) + "Z";
        timeText = "<small>" + time.getDate() + " " + months[time.getMonth()] + ", " + time.getHours() + ":" + time.getMinutes() + "<small>";
        $("#chatbox").append(prefix2 + "This is a response" + suffix2 + timeText + "</div></div>");
        chatData.push({ sender: "remote", message: "This is a response", time: ISOTime });

        console.log(chatData);
      }

      function downloadToJSON() {
        const a = document.createElement("a");
        const data = new Blob([JSON.stringify(chatData)], { type: "text/plain" });

        a.href = URL.createObjectURL(data);
        a.download = "chat.json";
        a.click();
      }

      function appendZero(value) {
        if (String(value).length == 1) {
          return "0" + value;
        }

        return value;
      }
    </script>
  </head>
  <body>
    <div class="row row-cols-2 w-100" style="height: 100vh">
        
      <div class="col-8 p-0" style="height: calc(100% - 110px); margin: auto;"">
        
        <div class="d-flex justify-content-between px-4 py-2 w-100 border-bottom border-primary border-4" style="height: 60px">
            <div class="a"> 
                <a href="/">
                    <img src="assets/img/previous.png" style="width: 20px;" alt="">
                </a>
                
            </div>
            <h4 style="margin-right: 200px; color: green;">Julian Irvy</h4>
          <div>
            <input id="json-upload" class="btn btn-success" type="file" />
            <button class="btn btn-success" onclick="downloadToJSON()">Save to JSON</button>
          </div>
        </div>
        <div id="chatbox" class="p-2">
          <div class="d-flex flex-row-reverse mb-4">
            <div class="message-container">
              <div class="my-message px-2 py-1 border border-dark rounded-1">
                Hi!
              </div>
              <small>10 May, 15:15</small>
            </div>
          </div>
          <div class="d-flex justify-content-start mb-4">
            <div class="message-container">
              <div class="sender-message px-2 py-1 border border-dark rounded-1">
               Hi!
              </div>
              <small>10 May, 15:15</small>
            </div>
          </div>
        </div>
        <div class="row mx-2 p-2 border-top border-info border-1" id="messagebox">
          <textarea style="height: 100%; resize: none" class="col-11 border border-dark border-1 rounded-2"></textarea>
          <div class="col-1">
            <button class="btn btn-success" onclick="sendMessage()">Send</button>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
