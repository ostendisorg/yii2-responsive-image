<?php


namespace ostendisorg\ResponsiveImage\widgets;


use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class Picture
 *
 * @package   ostendisorg\ResponsiveImage\widgets
 * @copyright 2021 Ostendis AG
 * @author    Tom Lutzenberger <lutzenbergertom@gmail.com>
 */
class Picture extends Widget
{
    /**
     * @var string[]
     */
    public $presets = [];

    /**
     * @var array
     */
    public $pictureOptions = [];

    /**
     * @var array
     */
    public $imageOptions = [];

    /**
     * @var string
     */
    public $image;


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        /** @var \ostendisorg\ResponsiveImage\components\ResponsiveImage $ri */
        $ri = Yii::$app->responsiveImage;
        $sources = '';

        if (!isset($this->image) || count($this->presets) === 0) {
            return;
        }

        foreach ($this->presets as $presetName) {
            $thumbnail = $ri->getThumbnail($this->image, $presetName);
            $preset = $ri->getPreset($presetName);
            $media = [];

            if ($preset->breakpointMin > -1) {
                $media[] = sprintf('(min-width: %dpx)', $preset->breakpointMin);
            }
            if ($preset->breakpointMax > -1) {
                $media[] = sprintf('(max-width: %dpx)', $preset->breakpointMax);
            }


            if ($thumbnail === null) continue;

            $sources .= Html::tag('source', '', [
                'srcset' => $thumbnail . ' 1x',
                'media'  => implode(' AND ', $media),
            ]);
        }

        echo Html::beginTag('picture', $this->pictureOptions);
        echo $sources;
        echo Html::img(Yii::getAlias($this->image), $this->imageOptions);
        echo Html::endTag('picture');
    }
}
