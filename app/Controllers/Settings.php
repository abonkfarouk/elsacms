<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use CodeIgniter\HTTP\ResponseInterface;

class Settings extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    /**
     * Display settings page
     */
    public function index()
    {
        $data = [
            'title' => 'Site Settings',
            'settings' => $this->settingModel->getAllSettingsGrouped(),
        ];

        return view('settings/index', $data);
    }

    /**
     * Update settings
     */
    public function update()
    {
        $input = $this->request->getPost();
        $files = $this->request->getFiles();
        
        log_message('error', 'Settings Update Input: ' . json_encode($input));
        
        try {
            $updateCount = 0;
            $failCount = 0;
            
            // Handle Logo Upload
            if ($file = $this->request->getFile('logo')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    helper('upload');
                    $result = upload_image($file, 'uploads/branding', [
                        'maxSize' => 2048,
                        'maxWidth' => 500,
                        'maxHeight' => 500,
                    ]);
                    
                    if ($result['success']) {
                        $oldLogo = $this->settingModel->getSetting('site_logo');
                        if ($oldLogo) {
                            delete_image($oldLogo);
                        }
                        $this->settingModel->updateSetting('site_logo', $result['path']);
                        $updateCount++;
                        log_message('error', "Logo updated: " . $result['path']);
                    } else {
                        $failCount++;
                        log_message('error', "Logo upload failed: " . $result['error']);
                    }
                }
            }

            // Handle Favicon Upload
            if ($file = $this->request->getFile('favicon')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    helper('upload');
                    $result = upload_image($file, 'uploads/branding', [
                        'maxSize' => 512,
                        'maxWidth' => 64,
                        'maxHeight' => 64,
                    ]);
                    
                    if ($result['success']) {
                        $oldFavicon = $this->settingModel->getSetting('site_favicon');
                        if ($oldFavicon) {
                            delete_image($oldFavicon);
                        }
                        $this->settingModel->updateSetting('site_favicon', $result['path']);
                        $updateCount++;
                        log_message('error', "Favicon updated: " . $result['path']);
                    } else {
                        $failCount++;
                        log_message('error', "Favicon upload failed: " . $result['error']);
                    }
                }
            }

            // Handle Hero Background Upload
            if ($file = $this->request->getFile('hero_bg_image')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    helper('upload');
                    $result = upload_image($file, 'uploads/hero', [
                        'maxSize' => 4096, // 4MB
                        'maxWidth' => 1920,
                        'maxHeight' => 1080,
                    ]);
                    
                    if ($result['success']) {
                        $oldHeroBg = $this->settingModel->getSetting('hero_bg_image');
                        if ($oldHeroBg) {
                            delete_image($oldHeroBg);
                        }
                        $this->settingModel->updateSetting('hero_bg_image', $result['path']);
                        $updateCount++;
                        log_message('error', "Hero BG Updated: " . $result['path']);
                    } else {
                        $failCount++;
                        log_message('error', "Hero BG upload failed: " . $result['error']);
                    }
                } elseif ($file->getError() !== UPLOAD_ERR_NO_FILE) {
                     // Log invalid file errors (e.g. size limit)
                     $failCount++;
                     log_message('error', "Hero upload invalid. Error Code: " . $file->getError() . ". Message: " . $file->getErrorString());
                }
            }

            // Handle Text Settings
            foreach ($input as $key => $value) {
                // Skip CSRF token
                if ($key === 'csrf_test_name') {
                    continue;
                }
                
                $result = $this->settingModel->updateSetting($key, $value);
                if ($result) {
                    $updateCount++;
                    log_message('error', "Setting updated: $key = $value");
                } else {
                    $failCount++;
                    log_message('error', "Setting update FAILED: $key = $value");
                }
            }
            
            if ($failCount > 0 && $updateCount == 0) {
                return redirect()->back()->withInput()->with('error', "Failed to update settings. See logs for details.");
            } elseif ($failCount > 0) {
                // If some failed (like image), show as warning/error so user notices
                $msg = "Settings updated partially! ($updateCount updated, $failCount failed). Check file size limits.";
                return redirect()->back()->withInput()->with('error', $msg);
            } else {
                return redirect()->back()->with('success', "Settings updated successfully! ($updateCount updated)");
            }
        } catch (\Exception $e) {
            log_message('error', 'Settings Update Exception: ' . $e->getMessage());
            return redirect()->to('/settings')->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Upload logo
     */
    public function uploadLogo()
    {
        helper('upload');
        
        $file = $this->request->getFile('logo');
        
        if (!$file || !$file->isValid()) {
            return redirect()->to('/settings')->with('error', 'Please select a valid logo file.');
        }
        
        $result = upload_image($file, 'uploads/branding', [
            'max_size' => 2048,
            'max_width' => 500,
            'max_height' => 500,
        ]);
        
        if ($result['success']) {
            // Delete old logo if exists
            $oldLogo = $this->settingModel->getSetting('site_logo');
            if ($oldLogo) {
                delete_image($oldLogo);
            }
            
            $this->settingModel->updateSetting('site_logo', $result['path']);
            return redirect()->to('/settings')->with('success', 'Logo uploaded successfully!');
        } else {
            return redirect()->to('/settings')->with('error', $result['error']);
        }
    }

    /**
     * Upload favicon
     */
    public function uploadFavicon()
    {
        helper('upload');
        
        $file = $this->request->getFile('favicon');
        
        if (!$file || !$file->isValid()) {
            return redirect()->to('/settings')->with('error', 'Please select a valid favicon file.');
        }
        
        $result = upload_image($file, 'uploads/branding', [
            'max_size' => 512,
            'max_width' => 64,
            'max_height' => 64,
        ]);
        
        if ($result['success']) {
            // Delete old favicon if exists
            $oldFavicon = $this->settingModel->getSetting('site_favicon');
            if ($oldFavicon) {
                delete_image($oldFavicon);
            }
            
            $this->settingModel->updateSetting('site_favicon', $result['path']);
            return redirect()->to('/settings')->with('success', 'Favicon uploaded successfully!');
        } else {
            return redirect()->to('/settings')->with('error', $result['error']);
        }
    }
}
