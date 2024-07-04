<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function register(Request $request)
    {
        $email = $request->input('email');

        // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu hay chưa
        $existingSubscription = Subscription::where('email', $email)->first();
        if ($existingSubscription) {
            return response()->json(['message' => 'Email này đã được đăng ký.'], 400);
        }

        $token = Str::uuid()->toString();

        try {
            $subscription = Subscription::create([
                'email' => $email,
                'token' => $token,
                'confirmed' => true,
            ]);

            Mail::to($email)->send(new WelcomeEmail($subscription));

            return response()->json(['message' => 'Email đã được đăng ký thành công và sẽ nhận thông tin dự báo thời tiết hàng ngày.'], 201);
        } catch (\Exception $e) {
            Log::error('Error registering email: ' . $e->getMessage());
            return response()->json(['message' => 'Error registering email.'], 500);
        }
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->input('email');

        $subscription = Subscription::where('email', $email)->first();
        if ($subscription) {
            $subscription->delete();
            return response()->json(['message' => 'Hủy đăng ký thành công.']);
        } else {
            return response()->json(['message' => 'Email không tồn tại.']);
        }
    }
}



