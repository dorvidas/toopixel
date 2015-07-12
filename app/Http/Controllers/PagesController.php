<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Contact;
use Illuminate\Support\Facades\Mail;

/**
 * Class PagesController
 * @package App\Http\Controllers
 */
class PagesController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function send(Request $request)
    {
        //Doing validation
        $this->validate($request, [
            'name' => 'required',
            'surname' => 'required',
            'photo' => 'required|image',
            'email' => 'required|email',
            'msg' => 'required',
        ]);

        $input = $request->all();

        //Sending mail
        Mail::send('emails.contact', $input, function($m) use ($request, $input)
        {
            $m->to($input['email'], $input['name'] . ' ' . $input['surname'])
                ->subject(trans('app.contact_form'))
                ->from('dorvidas@gmail.com ', 'TooPixel test')
                ->attach($request->file('photo')->getPathname(), ['as' => 'photo.' . $request->file('photo')->getClientOriginalExtension()])
                ->bcc('dorvidas@gmail.com', $name = null);
        });

        return view('index')->with(['notify' => trans('app.email_sent')]);

    }
}
