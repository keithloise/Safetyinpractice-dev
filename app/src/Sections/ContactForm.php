<?php

namespace {

    use SilverStripe\Control\Controller;
    use SilverStripe\Control\Email\Email;
    use SilverStripe\Forms\EmailField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\Form;
    use SilverStripe\Forms\FormAction;
    use SilverStripe\Forms\RequiredFields;
    use SilverStripe\Forms\TextareaField;

    class ContactForm extends Section
    {
        private static $singular_name = 'Contact Form';

        private static $allowed_actions = [
            'Form'
        ];

        public function Form()
        {
            $currentController = Controller::curr();

            $fields = FieldList::create(
                EmailField::create('Email'),
                TextareaField::create('Message')
            );
            $required = new RequiredFields('Email');
            $actions  = FieldList::create(
                FormAction::create('submit', 'Submit')
            );
            return Form::create($currentController, 'Form', $fields, $actions, $required);
        }

        public function submit($data, $form)
        {
            $email = new Email();

            $email->setTo('k8t.loise16@gmail.com');//Please change to relevant email
            $email->setFrom($data['Email']);
            $email->setSubject("Contact Message from {$data["Name"]}");

            $messageBody = "
            <p><strong>Name:</strong> {$data['Name']}</p>
            <p><strong>Message:</strong> {$data['Message']}</p>
             ";

            $email->setBody($messageBody);
            $email->send();

            $form->sessionMessage('Hello ' . $data['Name'], 'success');

            return $this->redirectBack();
        }
    }
}
