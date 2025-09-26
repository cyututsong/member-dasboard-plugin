<?php
// ✅ Shortcode: [moment_capture]
function moment_capture_upload() {

    if (is_admin()) return ''; // Prevent output in dashboard

    ob_start();

    // 🔹 Get event (user ID) and email from URL
    $user_id = isset($_GET['event']) ? intval($_GET['event']) : 0;
    $email   = isset($_GET['email']) ? sanitize_email($_GET['email']) : '';

    // 🔹 Validate QR scan
    if ($user_id === 0 || empty($email)) {
        return "<div class='divUploadContainer'>
                    <div class='innerUploadContainer'>
                        <p style='color:red; font-size:18px;'>🙏 Please scan the QR Code again.</p>
                    </div>
                </div>   
        ";
    }

    // 🔹 Verify user exists and email matches
    $user = get_userdata($user_id);
    if (!$user || strtolower($user->user_email) !== strtolower($email)) {
        return "<div class='divUploadContainer'>
                    <div class='innerUploadContainer'>
                        <p style='color:red; font-size:18px;'>🙏 Please scan the QR Code again.</p>
                    </div>
                </div>   
        ";
    }
    ?>
   <div class="moment-capture-wrapper">
        <form id="moment-capture-form" method="post" enctype="multipart/form-data">
            <input type="file" name="moment_images[]" id="moment_images" multiple accept="image/*" style="display:none;" />

            <div class="divUploadContainer">
                <div class="innerUploadContainer">
                    <img class="profile" src="https://localhost/bestwishes/wp-content/uploads/2025/06/serenity-g2.jpg" alt="Profile">

                    <p  id="moment-upload-message" class="simpleMessage">Please share your captured moments during our wedding. Thank you ❤️</p>

                    <!-- Preview + Message -->
                    <div id="moment-upload-preview"></div>
                    <p id="add-more-image" style="display:none; color:blue; cursor:pointer; text-decoration:underline; margin-top:10px;">➕ Add more image</p>
                    <button type="button" id="moment-upload-btn">Select Images</button>
                    <input type="submit" id="moment-submit-btn" name="submit_moment_images" value="Upload" style="display:none;" />

                </div>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function(){
        const fileInput   = document.getElementById("moment_images");
        const selectBtn   = document.getElementById("moment-upload-btn");
        const uploadBtn   = document.getElementById("moment-submit-btn");
        const addMoreLink = document.getElementById("add-more-image");
        const preview     = document.getElementById("moment-upload-preview");
        const form        = document.getElementById("moment-capture-form");
        const messageBox  = document.getElementById("moment-upload-message");

        let allFiles = [];

        // Open file picker
        selectBtn.addEventListener("click", () => fileInput.click());

        // Add images to preview
        fileInput.addEventListener("change", () => {
            if (fileInput.files.length > 0) {
                Array.from(fileInput.files).forEach(file => {
                    allFiles.push(file);

                    const reader = new FileReader();
                    reader.onload = e => {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.style.maxWidth = "150px";
                        img.style.margin = "5px";
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });

                // Show Upload + Add more, hide Select
                selectBtn.style.display = "none";
                uploadBtn.style.display = "inline-block";
                addMoreLink.style.display = "block";

                fileInput.value = ""; // reset
            }
        });

        // Add more images (reopen file selector)
        addMoreLink.addEventListener("click", () => {
            fileInput.click();
        });

        // Handle upload
        form.addEventListener("submit", function(e){
            if (allFiles.length > 0) {
                e.preventDefault();

                const formData = new FormData(form);
                allFiles.forEach(file => {
                    formData.append("moment_images[]", file, file.name);
                });
                formData.append("submit_moment_images", "1");

                fetch(window.location.href, { method: "POST", body: formData })
                .then(res => res.text())
                .then(() => {
                    allFiles = [];      // reset
                    preview.innerHTML = "";
                    messageBox.innerHTML = "🎉 Thanks for sharing your moments with us! Feel free to upload more anytime.";
                    messageBox.style.color = "green";

                    // Reset buttons
                    selectBtn.style.display = "inline-block";
                    uploadBtn.style.display = "none";
                    addMoreLink.style.display = "none";
                })
                .catch(err => console.error("Upload failed", err));
            }
        });
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('moment_capture', 'moment_capture_upload');


// ✅ Handle upload and save images
function moment_capture_handle_upload() {
    if (isset($_POST['submit_moment_images']) && !empty($_FILES['moment_images'])) {
        $upload_dir = wp_upload_dir();

        // 🔹 Get event (user ID) from URL
        $event_id = isset($_GET['event']) ? intval($_GET['event']) : 0;
        if ($event_id === 0) {
            echo "<p style='color:red; font-size:18px;'>🙏 Please scan the QR Code again.</p>";
            exit;
        }

        // 🔹 Target path per event
        $custom_subdir = '/moment_capture/event_' . $event_id;
        $target_dir    = $upload_dir['basedir'] . $custom_subdir;

        if (!file_exists($target_dir)) {
            wp_mkdir_p($target_dir);
        }

        $files = $_FILES['moment_images'];
        foreach ($files['name'] as $key => $value) {
            if ($files['name'][$key]) {
                $filename    = sanitize_file_name($files['name'][$key]);
                $filename    = wp_unique_filename($target_dir, $filename);
                $tmp_name    = $files['tmp_name'][$key];
                $target_file = $target_dir . '/' . $filename;

                move_uploaded_file($tmp_name, $target_file);
            }
        }

    }
}
add_action('wp', 'moment_capture_handle_upload');
