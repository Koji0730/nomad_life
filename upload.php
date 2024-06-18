<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Spot</title>
    <link rel="stylesheet" href="./upload.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXfmxHI2P_icXXsrbLZXz6pfQfHN92vDM&libraries=places"></script>
    <script src="./upload.js" defer></script>
</head>
<body>
    <div class="container">
        <h2>Upload a Tourist Spot Photo</h2>
        <form id="uploadForm" action="upload_photo.php" method="post">
            <div class="form-group">
                <label for="photo" class="custom-file-upload">
                    <input type="file" name="photo" id="photo" required>
                    Select Photo
                </label>
                <div id="drop-area" class="drop-area">
                    <p>Drag & Drop Photo Here</p>
                </div>
            </div>
            <div class="form-group">
                <label for="location">Location name:</label>
                <input type="text" name="location" id="location" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Register Spot">
                <input type="reset" value="Clear the form">
                <button onclick="location.href='http:localhost/nomad_life/index.html'">Make an Itinerary</button>
            </div>
        </form>
        <div id="preview" class="preview">
        </div>
    </div>
</body>
</html>