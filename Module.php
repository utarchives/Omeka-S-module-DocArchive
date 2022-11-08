<?php

namespace DocArchive;

use DocArchive\Form\ConfigForm;
use Omeka\Module\AbstractModule;
use Omeka\Module\Manager;
use Omeka\Module\Exception\ModuleCannotInstallException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractController;

class Module extends AbstractModule
{

    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);
        $acl = $this->getServiceLocator()->get('Omeka\Acl');

        $acl->allow(
            null,
            ['DocArchive\Controller\Site\Index',
            ]
            );
    }
    public function install(ServiceLocatorInterface $serviceLocator)
    {
        $omekaModules = $serviceLocator->get('Omeka\ModuleManager');
        $module = $omekaModules->getModule('ResourceTree');
        if (!$module) {
            throw new ModuleCannotInstallException('Require ResourceTree');
        }
        if (Manager::STATE_NOT_INSTALLED == $module->getState() || Manager::STATE_NOT_ACTIVE == $module->getState()) {
            throw new ModuleCannotInstallException('Require ResourceTree');
        }
        $module = $omekaModules->getModule('SpecialCharacterSearch');
        if (!$module) {
            throw new ModuleCannotInstallException('Require SpecialCharacterSearch');
        }
        if (Manager::STATE_NOT_INSTALLED == $module->getState() || Manager::STATE_NOT_ACTIVE == $module->getState()) {
            throw new ModuleCannotInstallException('Require SpecialCharacterSearch');
        }
    }
    public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
      $this->manageSettings($serviceLocator->get('Omeka\Settings'), 'uninstall');
        $this->manageSiteSettings($serviceLocator, 'install');
    }
    public function getConfigForm(PhpRenderer $renderer)
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');
        $settings = $services->get('Omeka\Settings');
        $formElementManager = $services->get('FormElementManager');
        $data = [];
        $defaultSettings = $config[strtolower(__NAMESPACE__)]['settings'];
        foreach ($defaultSettings as $name => $value) {
            $data['docarchive_config'][$name] = $settings->get($name);
        }
        $renderer->ckEditor();

        $form = $formElementManager->get(ConfigForm::class);
        $form->init();
        $form->setData($data);
        $html = $renderer->formCollection($form);
        return $html;
    }

    public function handleConfigForm(AbstractController $controller)
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');
        $settings = $services->get('Omeka\Settings');

        $params = $controller->getRequest()->getPost();

        $form = $this->getServiceLocator()->get('FormElementManager')
        ->get(ConfigForm::class);
        $form->init();
        $form->setData($params);
        if (!$form->isValid()) {
            $controller->messenger()->addErrors($form->getMessages());
            return false;
        }
        $defaultSettings = $config[strtolower(__NAMESPACE__)]['settings'];
        foreach ($params as $name => $value) {
            if (isset($defaultSettings[$name])) {
                $settings->set($name, $value);
            }
        }
    }
    /**
     *
     * @param $settings
     * @param $process
     * @param string $key
     */
    protected function manageSettings($settings, $process, $key = 'settings')
    {
        $config = require __DIR__ . '/config/module.config.php';
        $defaultSettings = $config[strtolower(__NAMESPACE__)][$key];
        foreach ($defaultSettings as $name => $value) {
            switch ($process) {
                case 'install':
                    $settings->set($name, $value);
                    break;
                case 'uninstall':
                    $settings->delete($name);
                    break;
            }
        }
    }
    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $process
     */
    protected function manageSiteSettings(ServiceLocatorInterface $serviceLocator, $process)
    {
        $siteSettings = $serviceLocator->get('Omeka\Settings\Site');
        $api = $serviceLocator->get('Omeka\ApiManager');
        $sites = $api->search('sites')->getContent();
        foreach ($sites as $site) {
            $siteSettings->setTargetId($site->id());
            $this->manageSettings($siteSettings, $process, 'site_settings');
        }
    }
    public function upgrade($oldVersion, $newVersion, ServiceLocatorInterface $serviceLocator)
    {

    }

    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {

    }

}
