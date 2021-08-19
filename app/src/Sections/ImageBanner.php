<?php

namespace {

    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\Image;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

    class ImageBanner extends Section
    {
        private static $singular_name = 'Image banner';

        private static $db = [
            'Content'         => 'HTMLText',
            'ContentPosition' => 'Varchar',
            'ImageAnimation'  => 'Varchar',
            'ImageOverlay'    => 'Varchar',
            'ImageHeight'     => 'Varchar',
        ];

        private static $has_one = [
            'Image' => Image::class
        ];

        private static $owns = [
            'Image'
        ];

        private static $defaults = [
            'ImageHeight' => 'bh-large'
        ];

        public function getSectionCMSFields(FieldList $fields)
        {
            $fields->addFieldToTab('Root.Main', $image = UploadField::create('Image', 'Banner image'));
            $image->setFolderName('Sections/Section_Banner/Images');
            $image->setAllowedExtensions(['png','gif','jpeg','jpg']);
            $fields->addFieldToTab('Root.Main', DropdownField::create('ImageHeight', 'Image height',
                array(
                    'bh-small' => 'Small',
                    'bh-medium'=> 'Medium',
                    'bh-large' => 'Large'
                )
            ));
            $fields->addFieldToTab('Root.Main', HTMLEditorField::create('Content'));
            $fields->addFieldToTab('Root.Main', DropdownField::create('ContentPosition', 'Content position',
                array(
                    'cp-left'   => 'Left',
                    'cp-center' => 'Center',
                    'cp-right'  => 'Right'
                )
            ));
            $fields->addFieldToTab('Root.Main', DropdownField::create('ImageOverlay', 'Add image overlay',
                array(
                    'none' => 'None',
                    'overlay-dark' => 'Dark',
                    'overlay-light'=> 'Light'
                )
            ));
            $fields->addFieldToTab('Root.Main', DropdownField::create('ImageAnimation', 'Animation',
                Animation::get()->filter('Archived', false)->map('Name', 'Name')));
        }
    }
}
