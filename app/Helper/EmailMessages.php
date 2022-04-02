<?php

namespace App\Helper;

class EmailMessages
{

    public $applicationname = "Fliar";

    public $baseurl = "https://test.com";


    public function sendEmailConfirmationEmail($name,$confirmationcode){

        return "<p>Hi, $name <br>
        Thanks for joining Flair! After an attempt to verify your details, you have been approved to continue setting up your Flair Employer account. Please activate your account below.
        <br><br>
        By activating this account, I confirm I represent HR, Recruiting, Marketing, PR, or I’m an executive at my company and I agree to Flair’s Terms of Service and Privacy Policy on behalf of my company.
            </p><br><br>
          <a href='$this->baseurl/confirmaccount/$confirmationcode'>Activate my account</a>


";




    }



    public function sendInvitationEmail($name,$confirmationcode){

        return "<p>Hi, $name <br>
        You have been invited to join Flair
        <br><br>
Click Link below to get starte            </p><br><br>
          <a href='$this->baseurl/resetpassword/$confirmationcode'>Go to Flair</a>


";




    }


    public function sendPasswordresetlink($name,$resetlinkcode){

        return "Hi, $name <br>
        Password rest request $this->applicationname. Click link to reset<br>
        $this->baseurl/resetpassword/$resetlinkcode

";




    }

}
