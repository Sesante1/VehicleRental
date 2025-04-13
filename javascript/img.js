(function () {
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

                reader.onload = function (e) {
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
                    deleteBtn.addEventListener('click', function () {
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
        window.addEventListener('beforeunload', function () {
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