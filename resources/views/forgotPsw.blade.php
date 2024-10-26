<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,300;1,400&display=swap"
        rel="stylesheet" />
    <title>Laravel</title>
</head>

<body
    style="
          font-size: 16px;
          font-weight: 400;
          line-height: 1.9;
          font-family: \'Roboto\', sans-serif;
        ">
    <div class="show" style="padding: 45px; background-color: #f5f5f5">
        <div style="background-color: #fff; padding: 45px">
            <div class="title"
                style="
                text-align: center;
                padding: 0 0 25px 0;
                font-size: 32px;
                font-weight: 600;
                letter-spacing: 0.3px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.12);
              ">
                {{$title}}
            </div>
            <div class="body">
                <h1 style="font-weight: 600; font-size: 24px">
                    Xin chào, {{$email}}
                </h1>
                <p style="margin-top: 15px; font-size: 16px">
                    Chúng tôi đã nhận được yêu cầu khôi phục mật khẩu cho tài khoản của bạn. Để tiếp tục, vui lòng sử dụng mã OTP dưới đây để xác thực và đặt lại mật khẩu:
                </p>
                <br>
                <h5 style="font-size: 20px">Mã OTP của bạn là: <span style="color: #2579f2">{{ $otp }}</span></h5>
                <p style="margin-top: 15px; font-size: 16px">
                    Mã OTP này sẽ hết hạn sau <b>15 phút</b>. Nếu bạn không thực hiện yêu cầu khôi phục mật khẩu, vui lòng bỏ qua email này. Tài khoản của bạn sẽ vẫn an toàn.
                </p>
                <p style="margin-top: 15px; font-size: 16px">Nếu bạn gặp bất kỳ vấn đề nào trong quá trình đặt lại mật khẩu, hãy liên hệ với đội ngũ hỗ trợ của chúng tôi.</p>
                <p style="margin-top: 15px; font-size: 16px">
                    Trân trọng <br />
                </p>
            </div>
        </div>
        <p style="text-align: center; margin-top: 25px">
            Hotline hỗ trợ: 0815416086
        </p>
    </div>
</body>

</html>
