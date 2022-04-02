<?php


namespace App\Helper;

// These must be at the top of your script, not inside a function
use App\Mail\FlairEmail;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHelper
{

    public static function sendEmail($emailtosend,$fullnametosend,$message,$title){

        //   dd($emailtosend);//check//again

        $mail = new PHPMailer(true);
        $email = $emailtosend;
        $fullname = $fullnametosend;
        try {
            $mail->SMPTOptions =  array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true

                )
            );
            //Server settings
            //$mail->SMTPDebug = 2;                                       // Enable verbose debug output
            $mail->isSMTP();
            $mail->Host       = env("F_EMAIL_HOST");  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = "AKIATC5LN3YT5CBQGOTY";// env("AWS_ACCESS_KEY_ID") ;                   // SMTP username
            $mail->Password   = "BCD2XpwuqHfRTn3FV3UE5qmRFSyEtJxtTxFfLJvXZ4Vu";// env("AWS_SECRET_ACCESS_KEY");                                 // SMTP password
            $mail->SMTPSecure = 'tls';   'ssl';                                   // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = '587';


            //Recipients
         //   $mail->setFrom('noreplyflair69@gmail.com', 'FLAIR');
          //  $mail->addAddress($email, $fullname);     // Add a recipient
         //   $mail->addReplyTo('noreplyflair69@gmail.com', 'FLAIR');

            $mail->setFrom('info@myflair.africa', 'FLAIR');
            $mail->addAddress($email, $fullname);     // Add a recipient
        //    $mail->addReplyTo('info@myflair.africa', 'FLAIR');
            //$mail->addCC('cc@example.com');

            //$mail->addCC('cc@example.com');

            // Attachments
            //$mail->addAttachment($file_location);         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $title;

            //   ob_start();




            $html = $message;// ob_get_clean();
            $mail->Body    = $html;
            $mail->AltBody = strip_tags($html);
            $mail->send();


            return 1;

            //   echo "Message sent successfully";

        } catch (Exception $e) {

        //    echo $e->getMessage();
               echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";  // OR take you to start all over



               die();

            return 0;
        }


    }





        public static function sendCUstomisedEmail($sendto, $title, $subject, $recievername, $sender, $message)
        {
            $mailInfo = new \stdClass();
            $mailInfo->recieverName = $recievername;
            $mailInfo->sender = $sender;
         //   $mailInfo->senderCompany = "CodeInnovers Technologies";
            $mailInfo->to = $sendto;
            $mailInfo->subject = $subject;
            $mailInfo->message = $message;

            //     $mailInfo->name = "Mike";
        //    $mailInfo->cc = "ci@email.com";
         //   $mailInfo->bcc = "jim@email.com";
         //   $mailInfo->bcc = "jim@email.com";
            $mailInfo->title = $title;



            Mail::to($sendto)
                ->send(new FlairEmail($mailInfo));
        }








}
