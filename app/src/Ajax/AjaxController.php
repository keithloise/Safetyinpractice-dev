<?php

namespace {

    use SilverStripe\Control\HTTPRequest;

    class AjaxController extends AbstractApiController
    {
        private static $allowed_actions = [
            'getResources'
        ];

        public function getResources(HTTPRequest $request)
        {
            return $this->jsonOutput();
        }
    }
}
