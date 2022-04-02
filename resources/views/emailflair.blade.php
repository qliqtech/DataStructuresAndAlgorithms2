<!DOCTYPE html>
<html>
<head>
    <link rel="preconnect" href="https://stijndv.com" />


</head>
<style>

    @import url('https://stijndv.com/fonts/Eudoxus-Sans.css');

    body {
        font-family: "Eudoxus Sans";
        margin: 0px;
    }

    .container {
        display: flex;
        flex-direction: column;
        height: 100vh;
        margin: 0px;
    }

    .head {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        background: #e7f3ef;
        padding: 0px 48px 32px;
    }

    #logo {
        margin-top: 70px;
    }

    h1 {
        font-family: "Eudoxus Sans";
        font-style: normal;
        font-weight: 700;
        font-size: 36px;
        line-height: 140%;
        letter-spacing: -0.02em;
        color: #000000;
        margin: 32px 32px 0px 0px;
    }
    #illustration {
        margin-top: 90px;
        margin-bottom: 32px;
    }

    button {
        font-family: "Eudoxus Sans";
        justify-content: center;
        align-items: center;
        padding: 14px 16px;
        font-size: 14px;
        min-width: 100%;
        height: 48px;
        color: white;
        background: #000000;
        border-radius: 2px;
        border: transparent;
        margin: 48px 0px;
    }
    a {
        font-family: "Eudoxus Sans";
        justify-content: center;
        align-items: center;
        padding: 14px 16px;
        font-size: 14px;
        min-width: 100%;
        height: 48px;
        color: white;
        background: #000000;
        border-radius: 2px;
        border: transparent;
        margin: 48px 0px;
    }
    .content {
        height: 380px;
        padding: 48px 48px 0px 48px;
        height: 60%;
    }

    .footer {
        display: flex;
        flex-direction: row;
        justify-content: space-between;

        margin: 0px 48px;
        border-top: 1px solid #e8e8e8;
        padding-top: 28px;
        color: #818181;
        font-size: 14px;
    }

    .copyright {
        display: flex;
    }


</style>

<body>
<div class="container">
    <div class="head">
        <div>
            <img src="https://s3.amazonaws.com/flair.africa.pub/emailtemplateresource/flair.png" id="logo" />
            <div style="margin-right: 32px">
                <h1>{!!$mailMessage->title!!}</h1>
            </div>
        </div>
        <div><img src="https://s3.amazonaws.com/flair.africa.pub/emailtemplateresource/group.png" id="illustration" /></div>
    </div>

    <div class="content">
        <p>

            <br /><br />

            {!!$mailMessage->message!!}

            <br />
        </p>

    </div>

    <div class="footer">
        <small style="font-size: 14px">Powered by Flair</small>
        <div class="copyright">
            <div><img src="https://s3.amazonaws.com/flair.africa.pub/emailtemplateresource/copyright.svg" style="margin-right: 10px" /></div>

            <small style="font-size: 14px; font-weight: bold"> Flair 2022</small>
        </div>
    </div>
</div>
</body>
</html>
