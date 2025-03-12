<?php
// Controller/RebrandingController.php
namespace MauticPlugin\MauticRebrandingBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RebrandingController extends FormController
{
    public function indexAction(Request $request)
    {
        $error   = null;
        $success = null;
        if ($request->isMethod('POST')) {
            $siteTitle = $request->request->get('siteTitle', 'Mautic');
            $mainColor = $request->request->get('mainColor', '#0000ff');
            $logoFile  = $request->files->get('logo');
            $logoUrl   = null;
            
            if ($logoFile) {
                // Define desired dimensions (change these values as needed)
                $desiredWidth  = 200;
                $desiredHeight = 50;
                list($width, $height) = getimagesize($logoFile->getPathname());
                
                if ($width !== $desiredWidth || $height !== $desiredHeight) {
                    $error = "The logo must be {$desiredWidth}px by {$desiredHeight}px. Uploaded image is {$width}px by {$height}px.";
                } else {
                    // Save the file in the plugin's assets/uploads folder
                    $uploadDir = __DIR__ . '/../Assets/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $filename = uniqid() . '.' . $logoFile->guessExtension();
                    $logoFile->move($uploadDir, $filename);
                    $logoUrl = '/plugins/MauticRebrandingBundle/Assets/uploads/' . $filename;
                }
            }
            
            // Only proceed if there is no error from logo validation
            if (!$error) {
                // Prepare settings data
                $data = [
                    'siteTitle' => $siteTitle,
                    'mainColor' => $mainColor,
                    'logoUrl'   => $logoUrl, // could be null if no new logo was uploaded
                ];
                // Save settings to a JSON file in the plugin folder (or use your preferred storage)
                $settingsFile = __DIR__ . '/../data/rebranding.json';
                if (!is_dir(dirname($settingsFile))) {
                    mkdir(dirname($settingsFile), 0777, true);
                }
                file_put_contents($settingsFile, json_encode($data));
                $success = "Settings saved successfully.";
            }
        }
        
        return $this->delegateView([
            'viewParameters'  => ['error' => $error, 'success' => $success],
            'contentTemplate' => 'MauticRebrandingBundle:Default:index.html.php',
        ]);
    }
}
