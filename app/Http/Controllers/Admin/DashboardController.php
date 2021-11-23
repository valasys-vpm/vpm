<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data = array();
    }

    public function index()
    {

        /*
        try {
            Mail::send('email.test', $details, function ($email) use ($details){
                $email->to(['sagar@valasys.com'])->subject('Email sending test');
            });
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }*/
        $details = ['name' => 'Hello World'];
        $to = array(
            'sagar@valasys.com',
            'tejaswi@valasys.com',
            'pravin@valasys.com'
        );
        try {
            dispatch(new SendEmailJob('email.test', $to, 'Annual Recognition Program!!', $details));
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }

        $this->data['message'] = 'sending mail....';
        return view('admin.dashboard', $this->data);
    }

}
