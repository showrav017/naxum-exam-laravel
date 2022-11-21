<?php

namespace App\Repositories;

use App\Interfaces\ContactsRepositoryInterface;
use App\Models\Contacts;

class ContactsRepository implements ContactsRepositoryInterface
{

    public function getAllContacts($created_by, $slug = "", $start_from=0, $page_limit=50)
    {
        $contactList = Contacts::where("removed", 0)->where("created_by", $created_by)->take($page_limit)->skip($start_from)->orderBy('updated_at', 'DESC');

        if(!empty($slug)){
            $contactList->where('name','LIKE','%'.$slug.'%');
        }

        $contactList = $contactList->get();

        $list = array();

        foreach ($contactList as $user)
        {
            $list[] = array(
                $user->name,
                $user->mobile_number,
                $user->phone_number,
                $user->work_number,
                date("F d, Y h:i a", strtotime($user->created_at)),
                date("F d, Y h:i a", strtotime($user->updated_at)),
                $user->contact_id
            );
        }

        $totalContactList = Contacts::where("removed", 0)->where("created_by", $created_by)->count();

        return [
            "contactList"=>$list,
            "totalContactList"=>$totalContactList,
            "filteredContactList"=>count($list),
        ];
    }

    public function getContactById($contact_id)
    {
        return Contacts::find("contact_id", $contact_id)->first();
    }

    public function deleteContact($contact_id)
    {
        $existingUser = Contacts::find($contact_id);
        $existingUser->removed = 1;
        $existingUser->save();
    }

    public function createContact($created_by, array $contactDetails)
    {
        $newUser = new Contacts();
        $newUser->contact_id = uniqid('').bin2hex(random_bytes(8));
        $newUser->name = $contactDetails["name"];
        $newUser->phone_number = $contactDetails["phone_number"];
        $newUser->mobile_number = $contactDetails["mobile_number"];
        $newUser->work_number = $contactDetails["work_number"];
        $newUser->email = $contactDetails["email"];
        $newUser->created_by = $created_by;
        $newUser->created_at = date("Y-m-d H:i:s");
        $newUser->save();
    }

    public function updateContact($contact_id, array $newContactDetails)
    {
        $existingContact = Contacts::find($contact_id);

        if(!empty($newContactDetails["name"]))
            $existingContact->name = $newContactDetails["name"];

        if(!empty($newContactDetails["phone_number"]))
            $existingContact->phone_number = $newContactDetails["phone_number"];

        if(!empty($newContactDetails["mobile_number"]))
            $existingContact->mobile_number = $newContactDetails["mobile_number"];

        if(!empty($newContactDetails["work_number"]))
            $existingContact->work_number = $newContactDetails["work_number"];

        if(!empty($newContactDetails["email"]))
            $existingContact->email = $newContactDetails["email"];

        $existingContact->updated_at = date("Y-m-d H:i:s");
        $existingContact->save();
    }
}
