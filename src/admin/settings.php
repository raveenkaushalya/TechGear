<?php
/**
 * Settings Page
 * 
 * Simple system configuration and settings management
 */

// Include the main layout component
require_once('components/main-layout.php');

// Include any other required components
require_once('components/page-header.php');
require_once('components/ui/card.php');

// Sample settings data (in a real app, this would come from a database)
$siteSettings = [
    'general' => [
        'siteName' => 'IndigoFlow',
        'siteDescription' => 'Modern enterprise management platform',
        'adminEmail' => 'admin@indigoflow.example',
        'timezone' => 'America/New_York',
        'dateFormat' => 'Y-m-d H:i:s'
    ],
    'appearance' => [
        'theme' => 'light',
        'accentColor' => '#5271ff',
        'logoUrl' => 'assets/images/logo.png'
    ],
    'notifications' => [
        'emailNotifications' => true,
        'adminAlerts' => true,
        'userActivityAlerts' => false,
        'paymentAlerts' => true
    ]
];

// Generate the settings page content
$pageContent = '
<div class="flex flex-col gap-8">
    ' . renderPageHeader('System Settings') . '
    
    <!-- Settings Navigation Tabs -->
    <div class="card">
        <div class="card-content p-0">
            <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                        General
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-controls="appearance" aria-selected="false">
                        Appearance
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false">
                        Notifications
                    </button>
                </li>
            </ul>
            
            <!-- Tab Content -->
            <div class="tab-content p-4" id="settingsTabsContent">
                <!-- General Settings Tab -->
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <h3 class="mb-4">General Settings</h3>
                    <form id="generalSettingsForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="siteName">Site Name</label>
                                    <input type="text" class="form-control" id="siteName" name="siteName" value="' . htmlspecialchars($siteSettings['general']['siteName']) . '">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="adminEmail">Admin Email</label>
                                    <input type="email" class="form-control" id="adminEmail" name="adminEmail" value="' . htmlspecialchars($siteSettings['general']['adminEmail']) . '">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="siteDescription">Site Description</label>
                            <textarea class="form-control" id="siteDescription" name="siteDescription" rows="2">' . htmlspecialchars($siteSettings['general']['siteDescription']) . '</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="timezone">Timezone</label>
                                    <select class="form-control" id="timezone" name="timezone">
                                        <option value="America/New_York" ' . ($siteSettings['general']['timezone'] === 'America/New_York' ? 'selected' : '') . '>Eastern Time (US & Canada)</option>
                                        <option value="America/Chicago" ' . ($siteSettings['general']['timezone'] === 'America/Chicago' ? 'selected' : '') . '>Central Time (US & Canada)</option>
                                        <option value="America/Denver" ' . ($siteSettings['general']['timezone'] === 'America/Denver' ? 'selected' : '') . '>Mountain Time (US & Canada)</option>
                                        <option value="America/Los_Angeles" ' . ($siteSettings['general']['timezone'] === 'America/Los_Angeles' ? 'selected' : '') . '>Pacific Time (US & Canada)</option>
                                        <option value="Europe/London" ' . ($siteSettings['general']['timezone'] === 'Europe/London' ? 'selected' : '') . '>London</option>
                                        <option value="Europe/Paris" ' . ($siteSettings['general']['timezone'] === 'Europe/Paris' ? 'selected' : '') . '>Paris</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="dateFormat">Date Format</label>
                                    <select class="form-control" id="dateFormat" name="dateFormat">
                                        <option value="Y-m-d H:i:s" ' . ($siteSettings['general']['dateFormat'] === 'Y-m-d H:i:s' ? 'selected' : '') . '>2025-09-10 14:30:00</option>
                                        <option value="m/d/Y h:i A" ' . ($siteSettings['general']['dateFormat'] === 'm/d/Y h:i A' ? 'selected' : '') . '>09/10/2025 02:30 PM</option>
                                        <option value="d/m/Y H:i" ' . ($siteSettings['general']['dateFormat'] === 'd/m/Y H:i' ? 'selected' : '') . '>10/09/2025 14:30</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions mt-4">
                            <button type="submit" class="button">Save General Settings</button>
                        </div>
                    </form>
                </div>
                
                <!-- Appearance Settings Tab -->
                <div class="tab-pane fade" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                    <h3 class="mb-4">Appearance Settings</h3>
                    <form id="appearanceSettingsForm">
                        <div class="form-group mb-3">
                            <label>Theme</label>
                            <div class="d-flex gap-3 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="theme" id="themeLight" value="light" ' . ($siteSettings['appearance']['theme'] === 'light' ? 'checked' : '') . '>
                                    <label class="form-check-label" for="themeLight">Light</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="theme" id="themeDark" value="dark" ' . ($siteSettings['appearance']['theme'] === 'dark' ? 'checked' : '') . '>
                                    <label class="form-check-label" for="themeDark">Dark</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="theme" id="themeSystem" value="system" ' . ($siteSettings['appearance']['theme'] === 'system' ? 'checked' : '') . '>
                                    <label class="form-check-label" for="themeSystem">System Default</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="accentColor">Accent Color</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <input type="color" id="accentColorPicker" value="' . htmlspecialchars($siteSettings['appearance']['accentColor']) . '" onchange="document.getElementById(\'accentColor\').value = this.value;">
                                </span>
                                <input type="text" class="form-control" id="accentColor" name="accentColor" value="' . htmlspecialchars($siteSettings['appearance']['accentColor']) . '">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="logoUrl">Logo URL</label>
                                    <input type="text" class="form-control" id="logoUrl" name="logoUrl" value="' . htmlspecialchars($siteSettings['appearance']['logoUrl']) . '">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Logo Preview</label>
                                    <div class="mt-2">
                                        <img src="' . htmlspecialchars($siteSettings['appearance']['logoUrl']) . '" alt="Site Logo" style="max-height: 60px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="logoUpload">Upload New Logo</label>
                                    <input type="file" class="form-control" id="logoUpload" name="logoUpload">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions mt-4">
                            <button type="submit" class="button">Save Appearance Settings</button>
                            <button type="button" class="button variant-outline" onclick="resetAppearance()">Reset to Defaults</button>
                        </div>
                    </form>
                </div>
                
                <!-- Notifications Settings Tab -->
                <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                    <h3 class="mb-4">Notification Settings</h3>
                    <form id="notificationSettingsForm">
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="emailNotifications" name="emailNotifications" ' . ($siteSettings['notifications']['emailNotifications'] ? 'checked' : '') . '>
                            <label class="form-check-label" for="emailNotifications">Enable Email Notifications</label>
                            <small class="form-text text-muted d-block">Send system notifications via email</small>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="adminAlerts" name="adminAlerts" ' . ($siteSettings['notifications']['adminAlerts'] ? 'checked' : '') . '>
                            <label class="form-check-label" for="adminAlerts">Admin Alerts</label>
                            <small class="form-text text-muted d-block">Send important system alerts to administrators</small>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="userActivityAlerts" name="userActivityAlerts" ' . ($siteSettings['notifications']['userActivityAlerts'] ? 'checked' : '') . '>
                            <label class="form-check-label" for="userActivityAlerts">User Activity Alerts</label>
                            <small class="form-text text-muted d-block">Send alerts for significant user activities</small>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="paymentAlerts" name="paymentAlerts" ' . ($siteSettings['notifications']['paymentAlerts'] ? 'checked' : '') . '>
                            <label class="form-check-label" for="paymentAlerts">Payment Alerts</label>
                            <small class="form-text text-muted d-block">Send alerts for payment events (success, failure, refund)</small>
                        </div>
                        
                        <div class="form-actions mt-4">
                            <button type="submit" class="button">Save Notification Settings</button>
                            <button type="button" class="button variant-outline ml-2" onclick="testEmailNotification()">Test Email Notification</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for settings functionality -->
<script>
    // Form submission handling
    document.addEventListener("DOMContentLoaded", function() {
        const forms = document.querySelectorAll("form");
        forms.forEach(form => {
            form.addEventListener("submit", function(e) {
                e.preventDefault();
                // Simulate form submission
                const formId = this.id;
                alert(`Settings saved for ${formId}`);
                // In a real app, you would send an AJAX request to save the settings
            });
        });
    });
    
    function resetAppearance() {
        if (confirm("Are you sure you want to reset appearance settings to defaults?")) {
            // In a real app, this would reset the form values to defaults
            document.getElementById("themeLight").checked = true;
            document.getElementById("accentColor").value = "#5271ff";
            document.getElementById("accentColorPicker").value = "#5271ff";
            alert("Appearance settings reset to defaults");
        }
    }
    
    function testEmailNotification() {
        // In a real app, this would send a test email
        alert("Sending test email to admin@indigoflow.example...");
        setTimeout(() => {
            alert("Test email sent successfully!");
        }, 1000);
    }
</script>';

// Set page title
$pageTitle = 'IndigoFlow - System Settings';

// Page-specific JavaScript
$pageSpecificJS = ['assets/js/pages/settings.js'];

// Include header
include_once('includes/header.php');

// Render the layout with our content
$fullPage = renderMainLayout($pageContent);

// Output the layout content
echo $fullPage;

// Include footer
include_once('includes/footer.php');
?>
