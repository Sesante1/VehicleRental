<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style/general.css">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <form action="" id="AddCarForm" class="add-form">
        <h3>Basic Information</h3>
        <div class="input-container-grid add-container">
            <div class="input">
                <label for="">Make</label>
                <input type="text" placeholder="e.g. Toyota" required>
            </div>
            <div class="input">
                <label for="">Model</label>
                <input type="text" placeholder="e.g. Camry" required>
            </div>
            <div class="input">
                <label for="">Year</label>
                <input type="number" placeholder="e.g. 2021" required>
            </div>
            <div class="input">
                <label for="">Car Type</label>
                <input type="text" placeholder="e.g. SUV, Electric, Truck" required>
            </div>
        </div>
        <div class="listing-title add-container">
            <div class="input">
                <label for="">Listing Title</label>
                <textarea id="description" name="description" placeholder="Describe your car, its condition, special features, etc." require></textarea>
            </div>
        </div>
        <div class="flex-column add-container">
            <h4>Pricing & Location</h4>
            <div class="input-container-grid">
                <div class="input">
                    <label for="">Daily Rate (₱)</label>
                    <input type="number" placeholder="e.g. 50" required>
                </div>
                <div class="input">
                    <label for="">Location</label>
                    <input type="text" placeholder="e.g. San Francisco, CA" required>
                </div>
            </div>
        </div>
        <div class="flex-column add-container">
            <h4>Specifications</h4>
            <div class="input-container-grid">
                <div class="input">
                    <label for="">Transmission</label>
                    <select name="" id="" required>
                        <option value="Manual">Manual</option>
                        <option value="Automatic">Automatic</option>
                    </select>
                </div>
                <div class="input">
                    <label for="">Number of Seats</label>
                    <select name="" id="" required>
                        <option value="two">2</option>
                        <option value="four">4</option>
                        <option value="five">5</option>
                        <option value="seven">7</option>
                        <option value="eight">8+</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="flex-column add-container">
            <h4>Features</h4>
            <div class="flex">
                <div>
                    <input type="checkbox" id="air-condition" name="vehicle1" value="air-condition">
                    <label for="air-condition"> &nbsp; Air Conditioning </label><br>
                    <input type="checkbox" id="navigation-system" name="vehicle2" value="navigation-system">
                    <label for="navigation-system"> &nbsp; Navigation System </label><br>
                    <input type="checkbox" id="heated-seats" name="vehicle3" value="heated-seats">
                    <label for="heated-seats"> &nbsp; Heated Seats </label><br>
                    <input type="checkbox" id="apple-carplay" name="vehicle3" value="apple-carplay">
                    <label for="apple-carplay"> &nbsp; Apple CarPlay </label><br>
                </div>
                <div>
                    <input type="checkbox" id="bluetooth" name="vehicle1" value="bluetooth">
                    <label for="bluetooth"> &nbsp; Bluetooth</label><br>
                    <input type="checkbox" id="leather-seats" name="vehicle2" value="leather-seats">
                    <label for="leather-seats"> &nbsp; Leather Seats</label><br>
                    <input type="checkbox" id="camera" name="vehicle3" value="camera">
                    <label for="camera"> &nbsp; Backup Camera</label><br>
                    <input type="checkbox" id="android" name="vehicle3" value="android">
                    <label for="android"> &nbsp; Android Auto</label><br>
                </div>
                <div>
                    <input type="checkbox" id="cruise-control" name="vehicle1" value="cruise-control">
                    <label for="cruise-control"> &nbsp; Cruise Control</label><br>
                    <input type="checkbox" id="sunroof" name="vehicle2" value="sunroof">
                    <label for="sunroof"> &nbsp; Sunroof</label><br>
                    <input type="checkbox" id="keyless" name="vehicle3" value="keyless">
                    <label for="keyless"> &nbsp; Keyless Entry</label><br>
                    <input type="checkbox" id="sound-system" name="vehicle3" value="sound-system">
                    <label for="sound-system"> &nbsp; Premium Sound System</label><br>
                </div>
            </div>
        </div>
        <div class="flex-column add-container">
            <h4>Availability</h4>
            <div class="input-container-grid">
                <div class="input">
                    <label for="">Available From</label>
                    <input type="date" placeholder="e.g. 50" required>
                </div>
                <div class="input">
                    <label for="">Available Until</label>
                    <input type="date" placeholder="e.g. San Francisco, CA">
                    <span>Leave empty if there's no end date</span>
                </div>
            </div>
        </div>

        <div class="car-uploader add-container" id="car-uploader">
            <h4>Car Images</h4>
            <p>Upload at least 3 high-quality images of your car (exterior, interior, etc.)</p>

            <div class="image-gallery" id="car-image-gallery">

                <div class="add-image-container" id="car-add-image-btn">
                    <div class="add-icon">+</div>
                    <div class="add-text">Add Image</div>
                </div>
            </div>

            <input type="file" id="car-file-input" class="file-input" accept="image/*" multiple>

            <div class="upload-count" id="car-upload-count">0 of 3 images selected</div>
            <div class="tips">
                <i class="fa-solid fa-circle-exclamation"></i>
                <h5>Tips for great car photos:</h5>
                <ul>
                    <li>Take photos in good lighting (daylight is best)</li>
                    <li>Include exterior (front, back, sides) and interior views</li>
                    <li>Make sure the car is clean and the background is not cluttered</li>
                    <li>Highlight any special features or unique aspects</li>
                </ul>
            </div>
        </div>

        <button class="submit">List Your Car</button>
    </form>
    <script>
        (function() {
            // Wait for DOM content to be loaded
            function initCarUploader() {
                const moduleId = 'car-uploader';

                // Check if the module exists in the DOM
                if (!document.getElementById(moduleId)) {
                    return; // Exit if the module doesn't exist in the current route
                }

                const gallery = document.getElementById('car-image-gallery');
                const addImageBtn = document.getElementById('car-add-image-btn');
                const fileInput = document.getElementById('car-file-input');
                const uploadCount = document.getElementById('car-upload-count');
                const uploadButton = document.getElementById('car-upload-button');

                let imageCount = 0;
                const minRequiredImages = 3;
                let imageData = []; // Store the actual files for submission

                // Handle add image button click
                function handleAddImageClick() {
                    fileInput.click();
                }

                // Handle file selection
                function handleFileChange(e) {
                    const files = e.target.files;

                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];

                        // Only process image files
                        if (!file.type.match('image.*')) continue;

                        const reader = new FileReader();

                        reader.onload = function(e) {
                            // Create image container
                            const container = document.createElement('div');
                            container.className = 'image-container';

                            // Create image element
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            container.appendChild(img);

                            // Store file data for later submission
                            const fileIndex = imageData.length;
                            imageData.push({
                                file: file,
                                previewUrl: e.target.result
                            });

                            // Create delete button
                            const deleteBtn = document.createElement('div');
                            deleteBtn.className = 'delete-btn';
                            deleteBtn.innerHTML = '×';
                            deleteBtn.dataset.index = fileIndex;
                            deleteBtn.addEventListener('click', function() {
                                container.remove();
                                // Mark as deleted rather than splicing to maintain indexes
                                imageData[fileIndex] = null;
                                imageCount--;
                                updateImageCount();
                            });
                            container.appendChild(deleteBtn);

                            // Insert before the add button
                            gallery.insertBefore(container, addImageBtn);
                            imageCount++;
                            updateImageCount();
                        };

                        // Read the image file as a data URL
                        reader.readAsDataURL(file);
                    }

                    // Reset file input
                    fileInput.value = '';
                }

                function updateImageCount() {
                    uploadCount.textContent = `${imageCount} of ${minRequiredImages} images selected`;

                    if (imageCount >= minRequiredImages) {
                        uploadButton.disabled = false;
                    } else {
                        uploadButton.disabled = true;
                    }
                }

                // Handle upload button click
                function handleUploadClick() {
                    // Filter out deleted images
                    const filesToUpload = imageData.filter(item => item !== null);

                    // In a real SPA, you might use fetch or axios here
                    console.log('Uploading files:', filesToUpload);
                    alert(`${imageCount} images ready to upload. In a real application, these would be sent to a server.`);

                    // Example of what a real upload might look like:
                    /*
                    const formData = new FormData();
                    filesToUpload.forEach((item, index) => {
                        formData.append(`carImage${index}`, item.file);
                    });
                    
                    fetch('/api/upload-car-images', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        // Navigate to next step using SPA router
                        // router.navigate('/listing-details');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                    */
                }

                // Add event listeners
                addImageBtn.addEventListener('click', handleAddImageClick);
                fileInput.addEventListener('change', handleFileChange);
                uploadButton.addEventListener('click', handleUploadClick);

                // Store the cleanup function to remove event listeners when component is unmounted
                return function cleanup() {
                    addImageBtn.removeEventListener('click', handleAddImageClick);
                    fileInput.removeEventListener('change', handleFileChange);
                    uploadButton.removeEventListener('click', handleUploadClick);
                    console.log('Car uploader component unmounted and cleaned up');
                };
            }

            // Initialize when DOM is loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCarUploader);
            } else {
                // DOM already loaded, initialize immediately
                const cleanup = initCarUploader();

                // For SPA route changes - example of how to clean up when navigating away
                // This would typically be handled by your SPA framework
                window.addEventListener('beforeunload', function() {
                    if (typeof cleanup === 'function') {
                        cleanup();
                    }
                });

                // Example of how to hook into SPA navigation events
                // Replace with your actual SPA router events
                /*
                document.addEventListener('spa:beforeRouteChange', function() {
                    if (typeof cleanup === 'function') {
                        cleanup();
                    }
                });
                */
            }
        })();
    </script>
</body>

</html>