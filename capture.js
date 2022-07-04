const video = document.getElementById('video');

var a=0;

        camOn();

function camOn(){
    navigator.mediaDevices.getUserMedia({
        audio: false, video: {
            width: {
                max:1280
            },
            height:{
                max:720
            }
        }
    }).then(stream => {
        video.srcObject = stream;
    }
    ).catch(console.error)
}

function camOff(){
    let stream = video.srcObject;
    let tracks = stream.getTracks();
    tracks.forEach(track => track.stop());
    video.srcObject = null;
}

var togglebtn = document.getElementById('camToggle');
togglebtn.addEventListener("click",function(){
    if (a==0){
        a=1;
        camOff();
    }
    else if (a==1){
        a=0;
        camOn();
    }
    console.log(a);
});