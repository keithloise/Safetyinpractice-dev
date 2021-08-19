<?php

namespace {

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
    }
}
