<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Conference with Elon Musk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/chat.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<style>
    .filter-blue {
        filter: invert(57%) sepia(64%) saturate(1977%) hue-rotate(158deg) brightness(102%) contrast(101%);
    }
</style>

<body class="bg-[url('assets/img/wp5709696-purple-gradient-wallpapers.jpg')] bg-cover">
    <div class="flex px-14 py-14">
        <div class="basis-1/2 mx-2">
            <video class="h-auto w-full object-cover" id="video" autoplay>Web Cam Unavailable</video>
            <h1 class="text-white mx-auto">Webcam not available since it requires HTTPS connection</h1>
        </div>
        <div class="basis-1/2 mx-2">
            <video controls autoplay muted loop class="h-full w-full object-cover">
                <source src="assets/video/Elon's SpaceX Tour.mp4" type="video/mp4">
            </video>
        </div>
    </div>
    <div>
        <img id="camToggle" class="mx-auto p-5 bg-white bg-opacity-20 w-24 h-auto rounded-full filter-blue opacity-90 cursor-pointer" src="assets/img/video-svgrepo-com.svg">
    </div>

    <!-- Contacts -->
    <div class="chat-bar-collapsible-left">
        <button id="chat-button" type="button" class="collapsible bg-teal-500">See Contacts
            <i class="fa fa-address-book" aria-hidden="true"></i>
        </button>

        <div class="content">
            <div class="full-chat-block">
                <!-- Message Container -->
                <div class="outer-container">
                    <div class="chat-container">

                        <!-- Messages -->
                        <div>
                            <a href="#">
                                <h2>Add new contact <i class="fa fa-plus" aria-hidden="true"></i></h2>
                            </a>
                            <div class="flex my-4">
                                <div class="mx-auto flex">
                                    <div class="my-auto mr-2"><img src="assets/img/profile-user-svgrepo-com.svg" alt=""></div>
                                    <div class="ml-2">
                                        <h3>Default User</h3>
                                        <h3>00000000</h3>
                                    </div>
                                    <div class="my-auto ml-2"><a href=""><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                                </div>
                            </div>
                            <div class="showCont"></div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- Chat Box -->
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
    <script src="assets/js/responses.js"></script>
    <script src="assets/js/chat.js"></script>

    <script>
        function getData() {
            $('.showCont').html('');
            $.ajax({
                url: "api/showConts",
                type: "GET",
                contentType: "application/json;charset=utf-8",
                dataType: "json",
                success: function(result) {
                    var html = '';
                    console.log(result.data);
                    $.each(result.data, function(key,item) {
                        html+= '<div class="flex my-4">';
                        html+= '<div class="mx-auto flex">';
                        html+= '<div class="my-auto mr-2"><img src="assets/img/profile-user-svgrepo-com.svg" alt=""></div>';
                        html+= '<div class="ml-2">';
                        html+= '<h3>'+item.name+'</h3>';
                        html+= '<h3>'+item.phonenum+'</h3>';
                        html+= '</div>';
                        html+= '<div class="my-auto ml-2"><a href=""><i class="fa fa-pencil" aria-hidden="true"></i></a></div></div></div>';

                    });
                    $('.showCont').html(html);
                },error: function(errormessage) {
                    alert(errormessage.responseText);
                }
            });
        }
        
        $(document).ready(function() {
            getData();
        });
    </script>
</body>

</html>