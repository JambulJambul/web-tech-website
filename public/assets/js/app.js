const video = document.querySelector('video');
const ss = document.querySelector('.btn-start');
const st = document.querySelector('.btn-stop');

ss.classList.add('add')
st.classList.add('remove')

const startWebCam = () =>{
 if (navigator.mediaDevices.getUserMedia) {
  navigator.mediaDevices.getUserMedia({ video: true })
  .then(stream => video.srcObject = stream).catch(error => console.log(error));
  ss.classList.add('remove')
  st.classList.remove('remove')
  ss.classList.remove('add')
 }
}
startWebCam();

const StopWebCam = ()=>{
  let stream = video.srcObject;
  let tracks = stream.getTracks();
  tracks.forEach(track => track.stop());
  video.srcObject = null;
  ss.classList.add('add')
  st.classList.add('remove')
}
