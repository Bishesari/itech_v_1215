<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درحال اتصال به درگاه</title>
    <style>
        /* مرکز کردن محتوا */
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: sans-serif;
            background-color: #f8f8f8;
            margin: 0;
        }

        p {
            margin-bottom: 20px;
            font-size: 1.1rem;
            color: #333;
        }

        /* Spinner ساده */
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #eee;
            border-top: 5px solid #3490dc; /* رنگ آبی */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<p>لطفاً چند لحظه صبر کنید، درحال انتقال به درگاه پرداخت...</p>
<div class="spinner"></div>

<form id="paymentForm" method="get" action="https://sep.shaparak.ir/Onlinepg/SendToken">
    <input type="hidden" name="token" value="{{ $token }}">
</form>

<script>
    // بعد از ۲٫۵ ثانیه فرم ارسال شود
    setTimeout(function() {
        document.getElementById('paymentForm').submit();
    }, 1500);
</script>
</body>
</html>
