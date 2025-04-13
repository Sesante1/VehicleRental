<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Image Selection</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            padding: 20px;
            background-color: #f8f9fa;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #212529;
        }

        p {
            margin-bottom: 20px;
            color: #495057;
        }

        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .image-container {
            position: relative;
            width: 200px;
            height: 150px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .delete-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 24px;
            height: 24px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 1px solid #dee2e6;
            font-size: 14px;
            color: #495057;
        }

        .delete-btn:hover {
            background-color: #f8f9fa;
        }

        .add-image-container {
            width: 200px;
            height: 150px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background-color: white;
        }

        .add-image-container:hover {
            border-color: #adb5bd;
            background-color: #f8f9fa;
        }

        .add-icon {
            font-size: 24px;
            color: #adb5bd;
            margin-bottom: 8px;
        }

        .add-text {
            font-size: 14px;
            color: #6c757d;
        }

        #file-input {
            display: none;
        }

        .upload-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .upload-button:hover {
            background-color: #0069d9;
        }

        .upload-count {
            margin-top: 10px;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <h1>Car Images</h1>
    <p>Upload at least 3 high-quality images of your car (exterior, interior, etc.)</p>

    <div class="image-gallery" id="image-gallery">
        <!-- Images will be added here dynamically -->

        <div class="add-image-container" id="add-image-btn">
            <div class="add-icon">+</div>
            <div class="add-text">Add Image</div>
        </div>
    </div>

    <input type="file" id="file-input" accept="image/*" multiple>

    <div class="upload-count" id="upload-count">0 of 3 images selected</div>

    <button class="upload-button" id="upload-button" disabled>Upload Images</button>

    <script src="../javascript/img.js"></script>
</body>

</html>