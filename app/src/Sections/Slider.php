<?php

namespace {

    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\GridField\GridField;
    use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
    use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
    use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
    use TractorCow\SliderField\SliderField;

    class Slider extends Section
    {
        private static $singular_name = 'Slider';

        private static $db = [
            'ItemsToShow' => 'Int',
            'Loop'        => 'Boolean',
            'Autoplay'    => 'Boolean',
        ];

        private static $has_many = [
            'SliderItems' => SliderItem::class
        ];

        public function getSectionCMSFields(FieldList $fields)
        {
            $fields->addFieldToTab('Root.Main', SliderField::create('ItemsToShow', 'Slider items to show', '1', '4'));
            $fields->addFieldToTab('Root.Main', CheckboxField::create('Loop', 'Enable slider loop'));
            $fields->addFieldToTab('Root.Main', CheckboxField::create('Autoplay', 'Enable autoplay'));

            $gridConfig = GridFieldConfig_RecordEditor::create(999);
            if($this->SliderItems()->Count())
            {
                $gridConfig->addComponent(new GridFieldOrderableRows());
            }
            $gridConfig->addComponent(new GridFieldEditableColumns());
            $gridColumns = $gridConfig->getComponentByType(GridFieldEditableColumns::class);
            $gridColumns->setDisplayFields([
                'Archived' => [
                    'title' => 'Archive',
                    'callback' => function($record, $column, $grid) {
                        return CheckboxField::create($column);
                    }]
            ]);

            $gridField = GridField::create(
                'SliderItems',
                'Slider items',
                $this->SliderItems(),
                $gridConfig
            );

            $fields->removeByName("SliderItems");
            $fields->addFieldToTab('Root.Main', $gridField);
        }

        public function getVisibleSliderItems()
        {
            return $this->SliderItems()->filter('Archived', false)->sort('Sort');
        }
    }
}
