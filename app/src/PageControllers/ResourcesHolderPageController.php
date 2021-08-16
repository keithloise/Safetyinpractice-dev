<?php

namespace {

    class ResourcesHolderPageController extends PageController
    {
        private static $default_child = ResourcesPage::class;

        private static $allowed_children = [
            ResourcesPage::class
        ];
    }
}
