<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Video Conference</title>
    <script src="https://kit.fontawesome.com/e391ce7786.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="assets/style/style.css" />
    <script src="assets/js/app.js" defer></script>
  </head>
  <body>
    <div class="container">
      <video autoplay></video>
      <video src="assets/video/video.mkv" autoplay loop muted></video>
    </div>

    <section>
      <div class="buttons">
        <button href="#!" class="btn-start" onclick="startWebCam()">
          <i class="bi bi-camera-video-fill"></i>
        </button>
        <button href="#!" class="btn-stop" onclick="StopWebCam()">
          <i class="bi bi-camera-video-off-fill"></i>
        </button>
      </div>
      <div class="buttons">
        <a href="chat">
          <button href="" class="btn-start">
            <i class="bi bi-chat-dots-fill"></i>
          </button>
        </a>
      </div>
      <div class="buttons">
        <a href="contacts">
          <button href="" class="btn-start">
            <i class="bi bi-person-lines-fill"></i>
          </button>
        </a>
      </div>
    </section>
  </body>
</html>
