document.addEventListener("DOMContentLoaded", async function () { 
    const video = document.getElementById("video");
    const canvas = document.getElementById("canvas");
    const ctx = canvas.getContext("2d");
    const faceCountText = document.getElementById("face-count");
    const eyeDirectionText = document.getElementById("eye-direction");
    const warningMessage = document.getElementById("warning-message");
    
    let lastEyeDirection = "Straight";
    let eyeDirectionTime = 0;
    let alertCount = 0;
    let noFaceAlertCount = 0;
    let fullscreenExitCount = 0;
    let lastFaceDetectedTime = Date.now();
    let speechWarningCount = 0;

    const audioContext = new (window.AudioContext || window.AudioContext)();
    const analyser = audioContext.createAnalyser();
    analyser.fftSize = 256;
    const dataArray = new Uint8Array(analyser.frequencyBinCount);

    async function enterFullScreen() {
        try {
            await document.documentElement.requestFullscreen();
        } catch (err) {
            console.warn("Fullscreen request denied: ", err);
            alert("⚠️ Fullscreen request denied: " + err.message);
        }
    }

    function showWarning(message) {
        warningMessage.textContent = message;
        warningMessage.style.display = "block";
        alert(message);
        setTimeout(() => {
            warningMessage.style.display = "none";
        }, 3000);
    }

    function checkFullScreen() {
        if (!document.fullscreenElement) {
            fullscreenExitCount++;
            if (fullscreenExitCount <= 3) {
                showWarning(`⚠️ Warning ${fullscreenExitCount}/3: Fullscreen mode exited!`);
            }
            if (fullscreenExitCount === 3) {
                showWarning("🚨 Maximum fullscreen exit warnings reached! Ending test.");
                exitPage();
            }
        }
    }

    document.addEventListener("fullscreenchange", checkFullScreen);

    async function setupCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: {
                    noiseSuppression: true,
                    echoCancellation: true,
                    autoGainControl: true
                }
            });

            video.srcObject = stream;
            video.muted = true;
            video.volume = 0;

            const microphone = audioContext.createMediaStreamSource(stream);

            // 🎚️ High-pass filter to remove background noise
            const highpassFilter = audioContext.createBiquadFilter();
            highpassFilter.type = "highpass";
            highpassFilter.frequency.value = 1000;

            microphone.connect(highpassFilter);
            highpassFilter.connect(analyser); // no connection to audioContext.destination (so no audio plays)

            return new Promise((resolve) => {
                video.onloadedmetadata = () => {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    resolve();
                };
            });
        } catch (error) {
            console.error("Error accessing webcam and microphone:", error);
            alert("Error accessing webcam and microphone: " + error.message);
        }
    }

    async function detectEyes() {
        const model = await blazeface.load();
        console.log("Model loaded successfully");

        setInterval(async () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const predictions = await model.estimateFaces(video, false);

            if (predictions.length > 0) {
                lastFaceDetectedTime = Date.now();
                noFaceAlertCount = 0;
                faceCountText.innerHTML = `<b>Faces:</b> ${predictions.length}`;
                let newEyeDirection = "Straight";

                predictions.forEach(face => {
                    const x = face.topLeft[0];
                    const y = face.topLeft[1];
                    const width = face.bottomRight[0] - x;
                    const height = face.bottomRight[1] - y;

                    ctx.strokeStyle = "red";
                    ctx.lineWidth = 3;
                    ctx.strokeRect(x, y, width, height);

                    if (face.landmarks) {
                        const leftEye = face.landmarks[0];
                        const rightEye = face.landmarks[1];

                        ctx.fillStyle = "blue";
                        ctx.beginPath();
                        ctx.arc(leftEye[0], leftEye[1], 3, 0, 2 * Math.PI);
                        ctx.arc(rightEye[0], rightEye[1], 3, 0, 2 * Math.PI);
                        ctx.fill();

                        const eyeCenterX = (leftEye[0] + rightEye[0]) / 2;
                        const faceCenterX = x + width / 2;

                        if (eyeCenterX < faceCenterX - 10) {
                            newEyeDirection = "Left";
                        } else if (eyeCenterX > faceCenterX + 10) {
                            newEyeDirection = "Right";
                        }
                    }
                });

                eyeDirectionText.innerHTML = `<b>Eye Direction:</b> Looking ${newEyeDirection}`;

                if (newEyeDirection !== "Straight") {
                    if (newEyeDirection !== lastEyeDirection) {
                        lastEyeDirection = newEyeDirection;
                        eyeDirectionTime = Date.now();
                    }

                    if (Date.now() - eyeDirectionTime > 2000) {
                        alertCount++;
                        if (alertCount <= 3) {
                            showWarning(`⚠️ Warning ${alertCount}/3: Please keep looking straight!`);
                        }
                        if (alertCount === 3) {
                            showWarning("🚨 Maximum warnings reached! Ending test.");
                            exitPage();
                        }
                    }
                } else {
                    lastEyeDirection = "Straight";
                }
            } else {
                faceCountText.innerHTML = `<b>Faces:</b> 0`;
                eyeDirectionText.innerHTML = `<b>Eye Direction:</b> Unknown`;

                if (Date.now() - lastFaceDetectedTime > 3000) {
                    noFaceAlertCount++;
                    if (noFaceAlertCount <= 3) {
                        showWarning(`🚨 No face detected! Warning ${noFaceAlertCount}/3.`);
                    }
                    if (noFaceAlertCount === 3) {
                        showWarning("🚨 Maximum warnings reached! Ending test.");
                        exitPage();
                    }
                    lastFaceDetectedTime = Date.now();
                }
            }
        }, 200);
    }

    function detectAudio() {
        setInterval(() => {
            analyser.getByteFrequencyData(dataArray);
            let sum = dataArray.reduce((a, b) => a + b, 0);
            let average = sum / dataArray.length;

            if (average > 40) {
                speechWarningCount++;
                if (speechWarningCount <= 3) {
                    showWarning(`🚨 Audio detected! Warning ${speechWarningCount}/3.`);
                }
                if (speechWarningCount === 3) {
                    showWarning('🚨 Maximum audio warnings reached! Ending test.');
                    exitPage();
                }
            }
        }, 100);
    }

    function exitPage() {
        window.open('', '_self');
        window.close();
        setTimeout(() => {
            window.location.href = "http://localhost/selector-applicant-project/applications.php";
        }, 2000);
    }

    await setupCamera();
    detectEyes();
    detectAudio();
});
