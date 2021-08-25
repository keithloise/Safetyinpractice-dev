<?php

namespace {

    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\Image;
    use SilverStripe\Core\ClassInfo;
    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\HiddenField;
    use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
    use SilverStripe\Forms\ListboxField;
    use SilverStripe\Forms\Tab;
    use SilverStripe\Forms\TabSet;
    use SilverStripe\Forms\TextField;
    use SilverStripe\ORM\ArrayList;
    use SilverStripe\ORM\DataObject;
    use SilverStripe\View\ArrayData;
    use TractorCow\Colorpicker\Color;
    use TractorCow\Colorpicker\Forms\ColorField;

    class Section extends DataObject
    {
        private static $default_sort = 'Sort';
        private static $singular_name = 'Content Section';

        private static $db = [
            'Name'    => 'Text',
            'Content' => 'HTMLText',
            'SectionType' => 'Varchar',
            'SectionWidth'=> 'Varchar',
            'SectionContainer' => 'Varchar',
            'SectionPadding'   => 'Varchar',
            'SectionBgType'    => 'Varchar',
            'SectionBgColor'   => Color::class,
            'SectionSecBgColor'=> Color::class,
            'Archived' => 'Boolean',
            'Sort'     => 'Int'
        ];

        private static $has_one = [
            'Page' => Page::class,
            'SectionBgImage' => Image::class
        ];

        private static $owns = [
            'SectionBgImage'
        ];

        private static $summary_fields = [
            'Name',
            'SectionWidth',
            'DisplaySectionType' => 'SectionType',
            'Status'
        ];

        private function getSectionTypes()
        {
            $sectionTypes = array();
            $classes =  ClassInfo::getValidSubClasses('Section');
            foreach ($classes as $type){
                $instance = self::singleton($type);
                $sectionTypes[$instance->ClassName] = $instance->singular_name();
            }
            return $sectionTypes;
        }

        public function getCMSFields()
        {
            $fields = new FieldList();
            $fields->push(TabSet::create("Root", $mainTab = Tab::create("Main")));

            if ($this->SectionType) {
                $fields->addFieldToTab('Root.Main',
                    $rot =  TextField::create('ROSectionType', 'Section type',
                        self::singleton($this->SectionType)->singular_name()));
                $rot->setDisabled(true);
            } else {
                $fields->addFieldToTab('Root.Main', DropdownField::create("SectionType", "Section type",
                    $this->getSectionTypes() , $this->ClassName));
            }

            if ($this->SectionType == 'Section') {
                $fields->addFieldToTab('Root.Main', HTMLEditorField::create('Content'));
            }

            $instance = self::singleton($this->SectionType);
            $instance->ID = $this->ID;
            $instance->getSectionCMSFields($fields);

            $fields->addFieldToTab('Root.Settings', DropdownField::create('SectionWidth', 'Section width',
                SectionWidth::get()->filter('Archived', false)->map('Class', 'Name')));

            $fields->addFieldToTab('Root.Settings', DropdownField::create('SectionContainer', 'Container width',
                array(
                    'container' => 'Fix width container',
                    'container-fluid' => '100% width container'
                )
            )->setDescription('<b>Fix-width</b>, which sets a max-width at each responsive breakpoint</br><b>Container fluid</b>, which is width: 100% at all breakpoints.</br>'));

            $fields->addFieldToTab('Root.Settings', ListboxField::create('SectionPadding', 'Section Paddings',
                Paddings::get()->map('Class', 'Name')));

            $fields->addFieldToTab('Root.Settings', DropdownField::create('SectionBgType', 'Section background type',
                array(
                    'none'             => 'None',
                    'bg-image' => 'Background image',
                    'bg-color' => 'Background color',
                    'bg-gradient' => 'Background gradient'
                )
            ));

            $fields->addFieldToTab('Root.Settings', $bgImage = UploadField::create('SectionBgImage', 'Section background image'));
                $bgImage->displayIf('SectionBgType')->isEqualTo('background-image')->orIf('SectionBgType')->isEqualTo('bac');
                $bgImage->setFolderName('Sections/Background');

            $fields->addFieldToTab('Root.Settings', $bgColor = ColorField::create('SectionBgColor', 'Section background color'));
                $bgColor->displayIf('SectionBgType')->isEqualTo('background-color');

            $fields->addFieldToTab('Root.Main', CheckboxField::create('Archived'));
            $fields->addFieldToTab('Root.Main', HiddenField::create('Sort'));

            return $fields;
        }

        public function getSectionCMSFields(FieldList $fields)
        {
            return $fields;
        }

        public function onBeforeWrite()
        {
            parent::onBeforeWrite();
            $this->ClassName = $this->SectionType;
            if($this->Name == ''){
                $this->Name = $this->SectionType;
            }
        }

        public function getDisplaySectionType()
        {
            return self::singleton($this->SectionType)->singular_name();
        }

        public function Show()
        {
            return $this->renderWith('Layout/Sections/' . $this->ClassName);
        }

        public function getDisplayTypeTrim()
        {
            return str_replace(' ','', self::singleton($this->SectionType)->singular_name());
        }

        public function getReadablePaddings()
        {
            $output = new ArrayList();
            $paddings = json_decode($this->SectionPadding);
            if ($paddings) {
                foreach ($paddings as $padding) {
                    $output->push(
                        new ArrayData(array('Name' => $padding))
                    );
                }
            }
            return $output;
        }

        public function getStatus()
        {
            if($this->Archived == 1) return _t('GridField.Archived', 'Archived');
            return _t('GridField.Live', 'Live');
        }
    }
}
