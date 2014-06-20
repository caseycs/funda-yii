<?php
//taken from https://github.com/Haensel/EMustache/ and a bit modified

/**
 * Mustache view renderer for the Yii PHP framework
 *
 * @author Johannes "Haensel" Bauer <thehaensel@gmail.com>
 *
 */
class EMustacheViewRenderer extends CApplicationComponent implements IViewRenderer
{
    /**
     * @var string the extension name of the view file. Defaults to '.mustache'.
     */
    public $fileExtension = '.mustache';
    public $mustachePathAlias = 'ext.EMustache.vendor.Mustache.src.Mustache';
    public $templatePathAlias = 'application.views';

    public $mustacheOptions = array();

    private $_m;

    public function init()
    {
        $this->_m = new Mustache_Engine(CMap::mergeArray(
                array(
                    'cache' => Yii::app()->getRuntimePath() . DIRECTORY_SEPARATOR . 'Mustache' . DIRECTORY_SEPARATOR . 'cache',
                    'partials_loader' => new Mustache_Loader_FilesystemLoader($this->getTemplatesPath(),
                        array('extension' => $this->fileExtension)),
                    'escape' => function($value)
                    {
                        return CHtml::encode($value);
                    },
                    'charset' => Yii::app()->charset,
                ), $this->mustacheOptions)
        );
    }

    private function getTemplatesPath()
    {
        $controller = Yii::app()->getController();
        $viewPath = $controller->getViewPath();

        if (Yii::app()->getTheme() !== null) {
            $viewPath = Yii::app()->getTheme()->getViewPath();
        }

        if ($controller->getModule() !== null) {
            $viewPath .= '/' . $controller->getModule()->getId();
        }

        return $viewPath;
    }

    /**
     * Renders a view file.
     * This method is required by {@link IViewRenderer}.
     * @param CBaseController $context the controller or widget who is rendering the view file.
     * @param string $sourceFile the view file path
     * @param mixed $data the data to be passed to the view
     * @param boolean $return whether the rendering result should be returned
     * @return mixed the rendering result, or null if the rendering result is not needed.
     */
    public function renderFile($context, $sourceFile, $data, $return)
    {
        if (!is_file($sourceFile) || ($file = realpath($sourceFile)) === false)
            throw new CException(Yii::t('yii', 'View file "{file}" does not exist.', array('{file}' => $sourceFile)));

        $rendered = $this->_m->render(file_get_contents($sourceFile), $data);

        if ($return) {
            return $rendered;
        } else
            echo $rendered;
    }
}
