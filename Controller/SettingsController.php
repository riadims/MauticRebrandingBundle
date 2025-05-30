<?php

namespace MauticPlugin\RebrandingBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class SettingsController extends CommonController
{   
    public function viewAction(): Response
    {
        $response = new Response();
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        
        $configFilePath = $this->getParameter('kernel.project_dir') . '/plugins/RebrandingBundle/Config/config.json';
        $config = [];

        if (file_exists($configFilePath)) {
            $config = json_decode(file_get_contents($configFilePath), true);
            $config['_version'] = filemtime($configFilePath);
        }

        return $this->delegateView([
            'viewParameters' => [
                'config' => $config,
            ],
            'contentTemplate' => '@Rebranding/Settings/index.html.twig',
            'passthroughVars' => [
                'activeLink' => 'rebranding_menu',
                'route' => 'rebranding',
            ],
        ], $response);
    }

    public function saveAction(Request $request): RedirectResponse
    {
        $configFilePath = $this->getParameter('kernel.project_dir') . '/plugins/RebrandingBundle/Config/config.json';
        $existingConfig = [];
    
        if (file_exists($configFilePath)) {
            $existingConfig = json_decode(file_get_contents($configFilePath), true) ?: [];
        }

        $data = $request->request->all();
        $file = $request->files->get('logo');

        $brandName = !empty($data['brand_name']) 
            ? trim($data['brand_name']) 
            : ($existingConfig['brand_name'] ?? 'Mautic');
            
        if (empty($brandName)) {
            $this->addFlash('error', 'Brand name cannot be empty.');
            return $this->redirectToRoute('rebranding');
        }

        $primaryColor = !empty($data['primary_color']) 
        ? trim($data['primary_color']) 
        : ($existingConfig['primary_color'] ?? '#4E5E9E');
        
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $primaryColor)) {
            $this->addFlash('error', 'Invalid primary color format.');
            return $this->redirectToRoute('rebranding');
        }

       
        $logoPath = $existingConfig['logo'] ?? null;
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

            if (is_dir($uploadDir)) {
                array_map('unlink', glob($uploadDir . 'logo_*'));
            }

            $fileName = uniqid('logo_') . '.' . $file->guessExtension();
            $file->move($uploadDir, $fileName);
            $logoPath = '/plugins/RebrandingBundle/Assets/img/' . $fileName;
        }

        $config = array_merge($existingConfig, [
            'brand_name' => $brandName,
            'primary_color' => $primaryColor,
            'logo' => $logoPath,
            '_updated' => time()
        ]);

        file_put_contents($configFilePath, json_encode($config, JSON_PRETTY_PRINT));

        $this->addFlash('notice', 'Rebranding settings updated successfully.');
        return $this->redirectToRoute('rebranding');
    }
    
    public function revertAction(Request $request): RedirectResponse
    {
    $configFilePath = $this->getParameter('kernel.project_dir') . '/plugins/RebrandingBundle/Config/config.json';
    
    try {
        if (file_exists($configFilePath) && !unlink($configFilePath)) {
            throw new \Exception('Failed to delete config file');
        }
        
        clearstatcache(true, $configFilePath);
        
        $this->addFlash('notice', 'Successfully reverted to default branding!');
        return $this->redirectToRoute('rebranding');
        
    } catch (\Exception $e) {
        $this->addFlash('error', 'Revert failed: ' . $e->getMessage());
        return $this->redirectToRoute('rebranding');
        }
    } 
} 
    