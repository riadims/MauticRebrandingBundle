<?php

namespace MauticPlugin\RebrandingBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SettingsController extends CommonController
{   
    /**
     * Displays the Rebranding settings page.
     */
    public function viewAction(): Response
    {
        $configFilePath = $this->getParameter('kernel.project_dir') . '/plugins/RebrandingBundle/Config/config.json';
        $config = [];

        if (file_exists($configFilePath)) {
            $config = json_decode(file_get_contents($configFilePath), true);
        }

        return $this->delegateView([
            'viewParameters'  => [
                'config' => $config,
            ],
            'contentTemplate' => '@Rebranding/Settings/index.html.twig',
            'passthroughVars' => [
                'activeLink'    => 'rebranding_menu',
                'route'         => 'rebranding',
            ],
        ]);
    }

    /**
     * Handles form submission and saves settings.
     */
    public function saveAction(Request $request): RedirectResponse
    {
    $data = $request->request->all();
    $file = $request->files->get('logo');

    $brandName = !empty($data['brand_name']) ? trim($data['brand_name']) : 'Mautic';
    if (empty($brandName)) {
        $this->addFlash('error', 'Brand name cannot be empty.');
        return $this->redirectToRoute('rebranding');
    }

    $primaryColor = !empty($data['primary_color']) ? trim($data['primary_color']) : '#4E5E9E';
    if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $primaryColor)) {
        $this->addFlash('error', 'Invalid primary color format.');
        return $this->redirectToRoute('rebranding');
    }

    $logoPath = null;
    if ($file) {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            $this->addFlash('error', 'Invalid file type. Only JPEG, PNG, and GIF are allowed.');
            return $this->redirectToRoute('rebranding');
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/plugins/RebrandingBundle/Assets/img/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $fileName = uniqid('logo_') . '.' . $file->guessExtension();
        $file->move($uploadDir, $fileName);
        $logoPath = '/plugins/RebrandingBundle/Assets/img/' . $fileName;
    }

    $config = [
        'brand_name'    => $brandName,
        'primary_color' => $primaryColor,
        'logo'          => $logoPath,
    ];

    $configFilePath = $this->getParameter('kernel.project_dir') . '/plugins/RebrandingBundle/Config/config.json';
    $configDir = dirname($configFilePath);
    if (!is_dir($configDir)) {
        mkdir($configDir, 0775, true);
    }

    file_put_contents($configFilePath, json_encode($config, JSON_PRETTY_PRINT));

    $this->addFlash('notice', 'Rebranding settings saved successfully.');

    return $this->redirectToRoute('rebranding');
    }

    public function revertAction(Request $request): JsonResponse
{
    $configFilePath = $this->getParameter('kernel.project_dir') . '/plugins/RebrandingBundle/Config/config.json';

    try {
        if (file_exists($configFilePath)) {
            if (unlink($configFilePath)) {
                return new JsonResponse(['success' => true]);
            } else {
                return new JsonResponse(['success' => false, 'message' => 'Failed to delete the config file.']);
            }
        } else {
            return new JsonResponse(['success' => false, 'message' => 'Config file does not exist.']);
        }
    } catch (\Exception $e) {
        return new JsonResponse(['success' => false, 'message' => $e->getMessage()]);
    }
}
    
}
