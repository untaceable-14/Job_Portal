<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-time Audio Detection</title>
</head>
<body>
    <h1>Real-time Audio Detection</h1>
    <p id="result">Listening for sounds...</p>

    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/speech-commands"></script>
    <script>
        async function startAudioDetection() {
            // Create a recognizer using a pre-trained model
            const recognizer = speechCommands.create('BROWSER_FFT');
            await recognizer.ensureModelLoaded();

            // Start listening and process results
            recognizer.listen(result => {
                const scores = result.scores;
                const labels = recognizer.wordLabels(); // "yes", "no", "up", "down", etc.
                const maxScoreIndex = scores.indexOf(Math.max(...scores));
                const detectedWord = labels[maxScoreIndex];

                document.getElementById('result').textContent = `Detected sound: ${detectedWord}`;
            }, {
                probabilityThreshold: 0.75,
                includeSpectrogram: true,
                overlapFactor: 0.5
            });

            console.log('Listening for sounds...');
        }

        // Automatically start listening when page loads
        window.addEventListener('load', startAudioDetection);
    </script>
</body>
</html>
