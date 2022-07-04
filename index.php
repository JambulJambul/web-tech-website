<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Conference with Elon Musk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="static/css/chat.css">
    <link rel="stylesheet" href="static/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<style>
    .filter-blue {
        filter: invert(57%) sepia(64%) saturate(1977%) hue-rotate(158deg) brightness(102%) contrast(101%);
    }
</style>

<body class="bg-[url('wp5709696-purple-gradient-wallpapers.jpg')] bg-cover">
    <div class="flex px-14 py-14">
        <div class="basis-1/2 mx-2">
            <video class="h-auto w-full object-cover" id="video" autoplay>Web Cam Unavailable</video>
            <h1 class="text-white mx-auto">Webcam not available since it requires HTTPS connection</h1>
        </div>
        <div class="basis-1/2 mx-2">
            <video controls autoplay muted loop class="h-full w-full object-cover">
                <source src="Elon's SpaceX Tour.mp4" type="video/mp4">
            </video>
        </div>
    </div>
    <div>
        <img id="camToggle" class="mx-auto p-5 bg-white bg-opacity-20 w-24 h-auto rounded-full filter-blue opacity-90 cursor-pointer" src="video-svgrepo-com.svg">
    </div>
    <div class="chat-bar-collapsible">
        <button id="chat-button" type="button" class="collapsible bg-yellow-400">Chat with Elon Musk!
            <i id="chat-icon" style="color: #fff;" class="fa fa-fw fa-comments-o"></i>
        </button>

        <div class="content">
            <div class="full-chat-block">
                <!-- Message Container -->
                <div class="outer-container">
                    <div class="chat-container">

                        <!-- Messages -->
                        <div id="chatbox">
                            <h5 id="chat-timestamp"></h5>
                            <p id="botStarterMessage" class="botText"><span>Loading...</span></p>
                        </div>

                        <!-- User input box -->
                        <div class="chat-bar-input-block">

                            <div class="chat-bar-icons flex-wrap">
                                <h3 id="txt-in" onclick="heartButton()">Hi there!</h3>
                                <h3 id="txt-in" onclick="heartButton2()">How are you?</h3>
                                <h3 id="txt-in" onclick="heartButton3()">What's your name?</h3>
                                <h3 id="txt-in" onclick="heartButton4()">What's your favorite food?</h3>
                                <h3 id="txt-in" onclick="heartButton5()">What's your favorite color?</h3>
                                <h3 id="txt-in" onclick="heartButton6()">What's your favorite animal?</h3>
                                <h3 id="txt-in" onclick="heartButton7()">Bye!</h3>
                            </div>
                        </div>

                        <div id="chat-bar-bottom">
                            <p></p>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
    <script async src="capture.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="static/scripts/responses.js"></script>
    <script src="static/scripts/chat.js"></script>
</body>

</html>