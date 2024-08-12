# WP Temp Countdown Pages Plugin

**Version:** 1.0  
**Author:** Hamdy Hussein

## Overview

The **WP Temp Countdown Pages** is a WordPress plugin designed to create and manage temporary countdown pages. Each page can have a unique URL and an end date and time. After the countdown ends, the page will be automatically redirected to the homepage. This plugin provides an easy-to-use interface in the WordPress admin dashboard for managing these countdown pages.

## Features

- **Create Countdown Pages**: Easily create new countdown pages with a custom title, URL, and end date and time.
- **Edit Countdown Pages**: Update the details of existing countdown pages.
- **Delete Countdown Pages**: Remove countdown pages that are no longer needed.
- **View All Countdown Pages**: A comprehensive list of all created countdown pages.
- **Redirect Expired Pages**: Automatically redirect expired countdown pages to the homepage.

## Installation

1. **Upload Plugin**:
   - Download the `WP-Temp-Countdown-Pages.zip` file.
   - Go to your WordPress admin dashboard.
   - Navigate to **Plugins > Add New**.
   - Click **Upload Plugin**, choose the `WP-Temp-Countdown-Pages.zip` file, and click **Install Now**.

2. **Activate Plugin**:
   - After installation, click **Activate** to enable the plugin on your WordPress site.

## Configuration

### Admin Menu

Once activated, the plugin adds a new menu item in the WordPress admin sidebar:

- **Countdown Pages**: The main menu item to manage your countdown pages.

### Create/Edit Countdown Page

1. **Navigate to Create/Edit Page**:
   - Go to **Countdown Pages > Add New** to create a new countdown page.
   - To edit an existing page, go to **Countdown Pages > All Pages**, and click **Edit** next to the desired page.

2. **Form Fields**:
   - **Page Title**: Enter the title for the countdown page.
   - **Page URL**: Set the URL slug for the countdown page.
   - **End Date and Time**: Specify the end date and time for the countdown in the format YYYY-MM-DD HH:MM:SS AM:PM.

3. **Save Changes**:
   - Click **Create Page** to add a new countdown page.
   - Click **Update Page** to modify an existing countdown page.

### View All Countdown Pages

1. **Navigate to All Countdown Pages**:
   - Go to **Countdown Pages > All Pages**.

2. **Table Overview**:
   - Displays all countdown pages with columns for Title, URL, End Date and Time, and Actions.
   - Use the **Edit** button to update a page or the **Delete** button to remove it.

## Date and Time Handling

- The end date and time are formatted and validated using the [Flatpickr](https://flatpickr.js.org/) JavaScript library.
- Pages are checked against the current date and time to determine if they are expired.

## Expired Page Redirection

- Pages that have reached their end date and time are automatically redirected to the homepage.

## Troubleshooting

- **Expired Pages Not Redirecting**:
  - Verify that your server's date and time are synchronized with your WordPress site's timezone settings.

- **Date Format Issues**:
  - Ensure the end date and time are entered in the correct format (YYYY-MM-DD HH:MM:SS AM:PM).

## Changelog

### Version 1.0

- Initial release with features for creating, editing, deleting, and managing countdown pages.

---

Happy countdowning!