const [preview, output, capture, result] = [
    document.getElementById('preview'),
    document.getElementById('output'),
    document.getElementById('capture'),
    document.getElementById('result')
  ];

  navigator.mediaDevices.getUserMedia({
    audio: false,
    video: {
      width: 400,
      height: 400
    }
  })
  .then(stream => {
    preview.srcObject = stream;
    preview.play();
  })
  .catch(error => {
    console.error(error);
  });

  capture.addEventListener('click', () => {
    const context = output.getContext('2d');
    output.width = 400;
    output.height = 400;

    context.drawImage(preview, 0, 0, output.width, output.height);
    result.src = output.toDataURL();
  });