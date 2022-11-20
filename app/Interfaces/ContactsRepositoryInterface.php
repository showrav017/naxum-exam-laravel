<?php

namespace App\Interfaces;

interface ContactsRepositoryInterface
{
    public function getAllContacts($start_from, $page_limit);
    public function getContactById($contact_id);
    public function deleteContact($contact_id);
    public function createContact(array $contactDetails);
    public function userNameExists($contact_name):bool;
    public function updateContactPassword($contact_id, array $newDetails);
}
