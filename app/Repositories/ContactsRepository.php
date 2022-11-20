<?php

namespace App\Repositories;

use App\Interfaces\ContactsRepositoryInterface;

class ContactsRepository implements ContactsRepositoryInterface
{

    public function getAllContacts($start_from, $page_limit)
    {
        // TODO: Implement getAllContacts() method.
    }

    public function getContactById($contact_id)
    {
        // TODO: Implement getContactById() method.
    }

    public function deleteContact($contact_id)
    {
        // TODO: Implement deleteContact() method.
    }

    public function createContact(array $contactDetails)
    {
        // TODO: Implement createContact() method.
    }

    public function userNameExists($contact_name): bool
    {
        // TODO: Implement userNameExists() method.
    }

    public function updateContactPassword($contact_id, array $newDetails)
    {
        // TODO: Implement updateContactPassword() method.
    }
}
