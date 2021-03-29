# Laravel SMSC

This package can be used for sending SMS-messages and SMS-codes through SMSC.ru

## Installation

```bash
composer require papalapa/laravel-smsc
```

```bash
php artisan vendor:publish --provider="Papalapa\Laravel\Smsc\SmscServiceProvider"
```

```bash
php artisan migrate
```

Next settings are available for change in ./config/smsc.php:

```php
return [

    // API URL
    'api_url' => 'https://smsc.ru/sys/send.php',

    // API Login and Password
    'api_login' => env('SMSC_LOGIN'),
    'api_password' => env('SMSC_PASSWORD'),

    // Sender name
    'sender_name' => env('SMSC_SENDER_NAME'),

    // Uses fake send
    // SMS will be stored in application log
    'fake_send' => env('SMSC_FAKE_SEND', true),

    // Code size
    // Max size is 6
    'code_size' => env('SMSC_CODE_SIZE', 6),
    
    // Lifetime of code and token in seconds
    'code_lifetime' => env('SMSC_CODE_LIFETIME', 120),
    'token_lifetime' => env('SMSC_TOKEN_LIFETIME', 600),

    // Connection for queueing jobs to be send
    // NULL - uses default app queue connection
    'queue_connection' => env('SMSC_QUEUE_CONNECTION'),
    
    // Throttle requests to using SMS-API
    // Max tries per minute
    'throttling_limit' => env('SMSC_THROTTLING_LIMIT', 2),
    
];
```

## How to use for SMS-code checking:

```php
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Papalapa\Laravel\Smsc\PhoneNumber;
use Papalapa\Laravel\Smsc\Services\MessageHandler;

class SmsController
{
    public function sendCode(
        Request $request,
        MessageHandler $messageHandler,
    ): array {
        $tel = PhoneNumber::fromString($request->input('tel'));
        $sms = $messageHandler->sendCode($tel);
        
        return ['sent_to' => $sms->phoneNumber()->numeric()];
    }
    
    public function checkCode(
        Request $request,
        MessageHandler $messageHandler,
    ): array {
        $tel = PhoneNumber::fromString($request->input('tel'));
        $code = $request->input('code');
        
        if ($messageHandler->validateCode($tel, $code)){
            $token = $messageHandler->generateToken($tel);
            return compact('token');
        }
        
        throw ValidationException::withMessages([
            'code' => 'Code is invalid',
        ]);
    }
}
```

You can use middleware to validate tokenized requests:

```php
use Illuminate\Support\Facades\Route;
use Papalapa\Laravel\Smsc\Middlewares\TokenizedPhoneNumber;

return [    
    Route::middleware(TokenizedPhoneNumber::class)
        ->post('proceed', [AccountController::class, 'proceedWithToken']);    
];
```

Then use decrypted-token-phone-number in controller action:

```php
use Illuminate\Http\Request;
use Papalapa\Laravel\Smsc\Middlewares\TokenizedPhoneNumber;

class AccountController
{
    public function proceedWithTokenUsingMiddleware(Request $request): array
    {
        $tel = TokenizedPhoneNumber::getPhoneNumber($request);
        
        return ['phone_number' => $tel->numeric()];
    }
}
```

or validate the token directly in controller action:

```php
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Papalapa\Laravel\Smsc\Exceptions\DecryptTokenException;
use Papalapa\Laravel\Smsc\Exceptions\ExpiredTokenException;
use Papalapa\Laravel\Smsc\Exceptions\InvalidTokenException;
use Papalapa\Laravel\Smsc\Services\TokenGenerator;

class AccountController
{
    public function proceedWithTokenAndWithoutMiddleware(
        Request $request,
        TokenGenerator $tokenGenerator,
    ): array
    {
        try {
            $tel = $tokenGenerator->validate($request->input('token'));
        } catch (InvalidTokenException|ExpiredTokenException|DecryptTokenException $e) {
            throw ValidationException::withMessages([
                'token' => $e->getMessage()
            ]);
        }
        
        return ['phone_number' => $tel->numeric()];
    }
}
```
