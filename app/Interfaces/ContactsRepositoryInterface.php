<?php

namespace App\Interfaces;

interface ContactsRepositoryInterface
{
    public function getAllContacts($created_by, $slug, $start_from, $page_limit);
    public function getContactById($contact_id);
    public function deleteContact($contact_id);
    public function createContact($created_by, array $contactDetails);
    public function updateContact($contact_id, array $newContactDetails);
}
