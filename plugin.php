<?php

class pluginInteractiveImage extends Plugin {

    public function init()
    {
        // Default configuration
        $this->dbFields = array(
            'imageUrl' => '',
            'redirectUrl' => '',
            'imageSize' => 150,
            'positionX' => '10',
            'positionY' => '80',
            'zIndex' => 999,
            'opacity' => 0.9
        );
    }

    public function form()
    {
        global $L;

        $html = '<div class="alert alert-info" role="alert">';
        $html .= $L->get('config-note');
        $html .= '</div>';

        // Image URL field
        $html .= '<div class="mb-3">';
        $html .= '<label class="form-label" for="imageUrl">' . $L->get('image-url') . '</label>';
        $html .= '<input class="form-control" id="imageUrl" name="imageUrl" type="text" value="' . $this->getValue('imageUrl') . '" placeholder="https://example.com/image.gif">';
        $html .= '<div class="form-text">' . $L->get('image-url-help') . '</div>';
        $html .= '</div>';

        // Redirect URL field
        $html .= '<div class="mb-3">';
        $html .= '<label class="form-label" for="redirectUrl">' . $L->get('redirect-url') . '</label>';
        $html .= '<input class="form-control" id="redirectUrl" name="redirectUrl" type="text" value="' . $this->getValue('redirectUrl') . '" placeholder="https://example.com/target-page">';
        $html .= '<div class="form-text">' . $L->get('redirect-url-help') . '</div>';
        $html .= '</div>';

        // Image size field
        $html .= '<div class="mb-3">';
        $html .= '<label class="form-label" for="imageSize">' . $L->get('image-size') . '</label>';
        $html .= '<input class="form-control" id="imageSize" name="imageSize" type="number" value="' . $this->getValue('imageSize') . '" min="50" max="500">';
        $html .= '<div class="form-text">' . $L->get('image-size-help') . '</div>';
        $html .= '</div>';

        // Horizontal position
        $html .= '<div class="mb-3">';
        $html .= '<label class="form-label" for="positionX">' . $L->get('position-x') . '</label>';
        $html .= '<input class="form-control" id="positionX" name="positionX" type="number" value="' . $this->getValue('positionX') . '" min="0" max="100" step="1">';
        $html .= '<div class="form-text">' . $L->get('position-x-help') . '</div>';
        $html .= '</div>';

        // Vertical position
        $html .= '<div class="mb-3">';
        $html .= '<label class="form-label" for="positionY">' . $L->get('position-y') . '</label>';
        $html .= '<input class="form-control" id="positionY" name="positionY" type="number" value="' . $this->getValue('positionY') . '" min="0" max="100" step="1">';
        $html .= '<div class="form-text">' . $L->get('position-y-help') . '</div>';
        $html .= '</div>';

        // Z-Index
        $html .= '<div class="mb-3">';
        $html .= '<label class="form-label" for="zIndex">' . $L->get('z-index') . '</label>';
        $html .= '<input class="form-control" id="zIndex" name="zIndex" type="number" value="' . $this->getValue('zIndex') . '" min="1" max="9999">';
        $html .= '<div class="form-text">' . $L->get('z-index-help') . '</div>';
        $html .= '</div>';

        // Opacity
        $html .= '<div class="mb-3">';
        $html .= '<label class="form-label" for="opacity">' . $L->get('opacity') . '</label>';
        $html .= '<input class="form-control" id="opacity" name="opacity" type="number" step="0.1" value="' . $this->getValue('opacity') . '" min="0.1" max="1.0">';
        $html .= '<div class="form-text">' . $L->get('opacity-help') . '</div>';
        $html .= '</div>';

        return $html;
    }

    public function siteBodyEnd()
    {
        $imageUrl = $this->getValue('imageUrl');
        $redirectUrl = $this->getValue('redirectUrl');

        // Don't display anything if no image URL is set
        if (empty($imageUrl)) {
            return false;
        }

        $imageSize = $this->getValue('imageSize');
        $positionX = $this->getValue('positionX');
        $positionY = $this->getValue('positionY');
        $zIndex = $this->getValue('zIndex');
        $opacity = $this->getValue('opacity');

        // Determine if this is a GIF
        $isGif = (strtolower(pathinfo($imageUrl, PATHINFO_EXTENSION)) === 'gif');

        $html = '
        <div id="interactive-image-container" style="
            position: fixed;
            left: ' . $positionX . '%;
            top: ' . $positionY . '%;
            z-index: ' . $zIndex . ';
            pointer-events: auto;
            cursor: pointer;
            transition: opacity 0.3s ease;
        ">
            <img id="interactive-image" 
                src="' . $imageUrl . '" 
                data-animated-src="' . $imageUrl . '"
                data-is-gif="' . ($isGif ? 'true' : 'false') . '"
                alt="Interactive Image" 
                style="
                    width: ' . $imageSize . 'px;
                    height: auto;
                    opacity: ' . $opacity . ';
                    display: block;
                    user-select: none;
                    -webkit-user-drag: none;
                "
            />
        </div>

        <script>
        (function() {
            var container = document.getElementById("interactive-image-container");
            var image = document.getElementById("interactive-image");

            if (!container || !image) return;

            // Check if image was previously closed in this session
            if (sessionStorage.getItem("interactiveImageClosed") === "true") {
                container.style.display = "none";
                return;
            }

            var clickCount = 0;
            var clickTimer = null;
            var firstClickDone = false;
            var isGif = image.getAttribute("data-is-gif") === "true";
            var animatedSrc = image.getAttribute("data-animated-src");
            var redirectUrl = "' . addslashes($redirectUrl) . '";

            // For GIFs: Initially freeze the animation by converting first frame to static
            if (isGif && !firstClickDone) {
                // We\'ll use a technique to stop GIF animation
                // The simplest way is to reload the image which resets the animation
                // For true "freeze", we would need canvas extraction, but that has CORS issues
                // Instead, we add a timestamp to prevent animation on initial load
                var staticUrl = animatedSrc + (animatedSrc.indexOf("?") > -1 ? "&" : "?") + "t=" + Date.now();
                image.src = staticUrl;
            }

            // Handle clicks
            container.addEventListener("click", function(e) {
                e.preventDefault();
                clickCount++;

                // Clear existing timer
                if (clickTimer) {
                    clearTimeout(clickTimer);
                }

                // Set a timer to detect if this is a triple-click
                clickTimer = setTimeout(function() {
                    if (clickCount >= 3) {
                        // Triple click - close the image
                        container.style.opacity = "0";
                        setTimeout(function() {
                            container.style.display = "none";
                            sessionStorage.setItem("interactiveImageClosed", "true");
                        }, 300);
                    } else if (clickCount === 1) {
                        // Single click - start animation and redirect
                        if (!firstClickDone) {
                            firstClickDone = true;
                            
                            // Start GIF animation by reloading with original URL
                            if (isGif) {
                                image.src = animatedSrc;
                            }
                        }

                        // Redirect to URL if provided
                        if (redirectUrl && redirectUrl.trim() !== "") {
                            window.open(redirectUrl, "_blank");
                        }
                    }
                    
                    // Reset click count
                    clickCount = 0;
                }, 400); // Wait 400ms to detect triple-click
            });

            // Optional: Add visual feedback on hover
            container.addEventListener("mouseenter", function() {
                image.style.opacity = "1";
            });

            container.addEventListener("mouseleave", function() {
                image.style.opacity = "' . $opacity . '";
            });
        })();
        </script>
        ';

        echo $html;
    }
}

?>
