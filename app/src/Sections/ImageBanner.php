<?php

namespace {

    use SilverStripe\Assets\Image;

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
    }
}
