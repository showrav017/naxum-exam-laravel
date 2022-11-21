<?php

namespace App\Http\Controllers;

use App\Interfaces\ContactsRepositoryInterface;
use Illuminate\Http\Request;

class ContactsController extends Controller
{

    private ContactsRepositoryInterface $contactRepository;

    public function __construct(ContactsRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function index(Request $request)
    {
        $start = $_POST["start"] ?? 0;
        $length = $_POST["length"] ?? 50;
        $draw = $_POST["draw"] ?? 50;
        $search = $_POST['search']['value'] ?? "";

        $contactList = $this->contactRepository->getAllContacts($request->loggedUserDetails['user_id'], $search, $start, $length);

        return $this->successResponse($contactList["contactList"], $draw, $contactList["filteredContactList"], $contactList["totalContactList"]);
    }

    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->contactRepository->createContact($request->loggedUserDetails['user_id'], $data);
        return $this->successResponse();
    }

    public function update(Request $request, $contact_id)
    {
        $data = json_decode($request->getContent(), true);
        $this->contactRepository->updateContact($contact_id, $data);
        return $this->successResponse();
    }

    public function remove(Request $request, $contact_id)
    {
        $this->contactRepository->deleteContact($contact_id);
        return $this->successResponse();
    }
}
