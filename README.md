# Bludit Interactive Image Plugin

A powerful Bludit CMS plugin that displays an interactive image (GIF or PNG) on your website with advanced click behaviors and customizable positioning.

## Features

✨ **Interactive Click Behaviors**
- **Single Click**: Redirects visitors to a custom URL (opens in new tab)
- **Triple Click**: Closes/hides the image for the current session
- **Animation Control**: GIF images remain static until first click, then animate

🎨 **Customization Options**
- Adjustable image size (50-500px width)
- Flexible positioning (X/Y coordinates as percentages)
- Configurable opacity (0.1-1.0)
- Z-index control for layer stacking
- Support for both GIF and PNG formats

🚫 **Non-Blocking Design**
- Fixed positioning that doesn't interfere with page content
- Configurable transparency to maintain content readability
- Hover effect for better visibility

## Requirements

- Bludit CMS 3.0 or higher
- PHP 5.6 or higher
- A theme that supports the `siteBodyEnd` hook

## Installation

### Method 1: Manual Installation

1. Download or clone this repository
2. Copy the `interactive-image` folder to your Bludit plugins directory:
   ```
   /bl-plugins/interactive-image/
   ```
3. Log in to your Bludit admin panel
4. Navigate to **Settings** → **Plugins**
5. Find "Interactive Image" and click **Activate**

### Method 2: Upload via Admin Panel

1. Create a ZIP file of the `interactive-image` folder
2. Log in to your Bludit admin panel
3. Navigate to **Settings** → **Plugins**
4. Click **Upload Plugin** and select your ZIP file
5. Activate the plugin

## Directory Structure

```
interactive-image/
├── languages/
│   └── en.json          # English language file
├── metadata.json        # Plugin metadata
├── plugin.php           # Main plugin file
└── README.md            # This file
```

## Configuration

After activation, click on the plugin name to access settings:

### Image URL
Enter the full URL of your image (GIF or PNG format).
- Example: `https://example.com/images/promo.gif`

### Redirect URL
The URL where visitors will be redirected when they click the image.
- Example: `https://example.com/special-offer`
- Opens in a new tab automatically

### Image Width (px)
Set the width of your image in pixels (50-500).
- Height scales automatically to maintain aspect ratio
- Default: 150px

### Horizontal Position (%)
Distance from the left edge of the screen (0-100%).
- 0% = Far left
- 50% = Center
- 100% = Far right
- Default: 10%

### Vertical Position (%)
Distance from the top edge of the screen (0-100%).
- 0% = Top
- 50% = Middle
- 100% = Bottom
- Default: 80%

### Layer Priority (Z-Index)
Stacking order of the image (1-9999).
- Higher numbers appear on top of lower numbers
- Default: 999

### Opacity
Image transparency level (0.1-1.0).
- 0.1 = Very transparent
- 1.0 = Fully opaque
- Default: 0.9

## Usage

### For Site Administrators

1. Configure the plugin settings in the admin panel
2. Enter your image URL and redirect URL
3. Adjust size and position to your preference
4. Save settings
5. Visit your website to see the interactive image

### For Site Visitors

- **View**: The image appears at the configured position
- **First Click**: 
  - GIF images start animating
  - Browser opens the redirect URL in a new tab
- **Triple Click**: Image closes and won't appear again during the current browser session
- **Hover**: Image becomes fully opaque for better visibility

## How It Works

### Click Detection
The plugin uses a sophisticated click detection system:
- Tracks clicks and timestamps
- Distinguishes between single and triple clicks
- 400ms window for triple-click detection

### GIF Animation Control
For GIF images:
1. Initial load: Image URL includes a timestamp parameter to reset animation
2. First click: Reloads with original URL to start animation
3. Subsequent clicks: Animation continues normally

### Session Persistence
Uses `sessionStorage` to remember if the user closed the image:
- Persists only for the current browser session
- Resets when user closes browser or tab
- No cookies or permanent storage used

## Theme Compatibility

This plugin works with any Bludit theme that supports the `siteBodyEnd` hook, which is standard in most modern Bludit themes.

## Browser Support

- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Opera (latest)

## Troubleshooting

### Image Not Appearing
- Verify the image URL is correct and accessible
- Check that the plugin is activated
- Ensure your theme supports the `siteBodyEnd` hook
- Check browser console for errors

### Image Blocking Content
- Reduce opacity to make underlying content more visible
- Adjust position to a less intrusive location
- Decrease image size

### Triple Click Not Working
- Ensure you're clicking rapidly (within 400ms)
- Some browsers may have different click timing
- Try clicking faster or slower

### Animation Not Starting
- Verify the image is actually a GIF (not a static PNG)
- Check that the GIF URL is correct
- Clear browser cache and reload

### Redirect Not Working
- Verify the redirect URL is properly formatted (include `https://`)
- Check browser popup blocker settings
- Ensure JavaScript is enabled

## Customization

### Adding Custom Styles
You can customize the appearance by adding CSS to your theme. Target these elements:
```css
#interactive-image-container {
    /* Container styles */
}

#interactive-image {
    /* Image styles */
}
```

### Adding More Languages
Create a new language file in the `languages/` folder:
```json
{
    "plugin-data": {
        "name": "Your Language Name",
        "description": "Your language description"
    }
}
```

## Changelog

### Version 1.0 (2026-01-09)
- Initial release
- Single click redirect functionality
- Triple click close/hide functionality
- GIF animation control (static until first click)
- Customizable size and positioning
- Opacity control
- Session-based persistence
- Hover effects

## Future Enhancements

Potential features for future versions:
- Mobile-specific positioning
- Animation speed control
- Multiple images support
- Scheduled display (time-based)
- Cookie-based persistence options
- Click counter analytics

## License

MIT License - Feel free to use and modify as needed.

## Author

Custom development for Bludit CMS

## Support

For issues, questions, or feature requests:
1. Check the Troubleshooting section above
2. Review Bludit's plugin documentation
3. Check your browser console for JavaScript errors

## Acknowledgments

- Inspired by the Bludit Floating Image Plugin
- Built following official Bludit plugin development guidelines
- Thanks to the Bludit community for their excellent documentation
