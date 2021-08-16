<?php

namespace {

    class ResourcesPage extends Page
    {
        private static $default_parent = ResourcesHolderPage::class;

        private static $can_be_root = false;

        private static $icon_class = 'font-icon-p-book';

        private static $singular_name = 'Resources Page';

        private static $plural_name = 'Resources Pages';
    }
}
