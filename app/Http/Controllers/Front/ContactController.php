<?php namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{

    public function create()
    {
        $subject_listing = [];

        return view('pages.contact',['subject_listing' => $subject_listing]);
    }

    public function store(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(),[
            'name' => 'required|min:2|max:255',
            'content' => 'required',
            'email' => 'required|email|min:5|max:255',
        ], [], [
            'name' => trans('app.contactform.name'),
            'firstname' => trans('app.contactform.firstname'),
            'email' => trans('app.contactform.email'),
            'company' => trans('app.contactform.company'),
            'subject' => trans('app.contactform.subject'),
            'content' => trans('app.contactform.content'),
        ]);

        if ($validator->fails()) {

            // Flag to indicate we should set the login-modal open
            Session::flash('contact_modal_error',true);

            $this->throwValidationException($request, $validator);
        }

        $email = $request->get('email');
        $firstname = cleanupString($request->get('firstname'));
        $name = cleanupString($request->get('name'));
        $company = cleanupString($request->get('company'));
        $subject = cleanupString($request->get('subject'));
        $content = cleanupHTML($request->get('content'),'<br>');

        Contact::make($email,$firstname,$name,$company,$subject,$content);

        $this->sendMailToAdmin([
            'email' => $email,
            'firstname' => $firstname,
            'name' => $name,
            'company' => $company,
            'subject' => $subject,
            'content' => $content,
        ]);

        return redirect()->back()->with('messages.success', trans('app.contactform.thanks',['name' => $firstname]));
    }

    private function sendMailToAdmin(array $data)
    {
        $email = config('bnpparibas.contact.email');
        $name = config('bnpparibas.contact.name');

        Mail::send(['emails.contact', 'emails.contact_plain'], ["data" => $data], function ($mail) use ($data, $email, $name)
        {
            $mail->to($email, $name)
                ->from($email, $name)
                ->replyTo($data["email"],$data['firstname'].' '.$data['name'])
                ->subject('Contactformulier Factoring: ' . $data['subject'] . ' [' . $data['firstname'].' '.$data['name'] . ']');
        });
    }
}
