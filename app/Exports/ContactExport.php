<?php

namespace App\Exports\Donator;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        //Put Here Header Name That you want in your excel sheet
        return [
            'Name',
            'Email',
            'Mobile',
            'City',
            'Zip Code',
            'Message',
            'IP Address',
            'Created Date',
        ];
    }

    public function collection()
    {
        $contactArray  = array();
        $contactDetail = Contact::orderBy('created_at', 'desc')->get()->toArray();
        if (count($contactDetail) > 0) {
            for ($c=0; $c < count($contactDetail); $c++) {
                $contactArray[] = array(
                    'name' => $contactDetail[$c]['contact_name'],
                    'email' => $contactDetail[$c]['contact_email'],
                    'mobile' => $contactDetail[$c]['contact_mobile'],
                    'city' => $contactDetail[$c]['contact_city'],
                    'zipcode' => $contactDetail[$c]['contact_zipcode'],
                    'message' => $contactDetail[$c]['contact_message'],
                    'ip' => $contactDetail[$c]['contact_ip'],
                    'created_at' => $contactDetail[$c]['created_at']
                );
            }
        }
        return collect($contactArray);
    }
}
