<?php

namespace {

    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\File;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\GridField\GridField;
    use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
    use SilverStripe\ORM\DataExtension;
    use TractorCow\SliderField\SliderField;

    class SiteConfigExtension extends DataExtension
    {
        private static $db = [
            'LogoWidth' => 'Int'
        ];

        private static $has_one = [
            'Logo' => File::class
        ];

        private static $owns = [
            'Logo'
        ];

        public function updateCMSFields(FieldList $fields)
        {
            $fields->addFieldToTab('Root.Header', UploadField::create('Logo')->setFolderName('Logo'));
            $fields->addFieldToTab('Root.Header', SliderField::create('LogoWidth', 'Logo width', '50', '350'));

            /*
             *  Section Width
             */
            $configWidth = GridFieldConfig_RecordEditor::create('999');
            $editorWidth = GridField::create('SectionWidth', 'Width', SectionWidth::get(), $configWidth);
            $fields->addFieldToTab('Root.Sections', $editorWidth);

            /*
             *  Section Padding
             */
            $configPadding = GridFieldConfig_RecordEditor::create('999');
            $editorPadding = GridField::create('SectionPadding', 'Padding', Paddings::get(), $configPadding);
            $fields->addFieldToTab('Root.Sections', $editorPadding);

            /*
             *  Pre-header Menu
             */
            $configHeaderMenu = GridFieldConfig_RecordEditor::create('999');
            $editorHeaderMenu = GridField::create('PreHeaderMenu', 'Pre-header menu', PreHeaderMenu::get(), $configHeaderMenu);
            $fields->addFieldToTab('Root.Header', $editorHeaderMenu);
        }
    }
}
