<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailVerificationController extends Controller
{
    /**
     * Verify user by email.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function verify(Request $request): Response
    {
        $emailVerificationRequest = EmailVerificationRequest::where(
            'salt', $request->input('salt')
        )->findOrFail($request->input('uuid'));

        $emailVerificationRequest->user->update(['is_email_verified' => true]);

        $emailVerificationRequest->delete();

        return response()->view('email-verification-success');
    }
}
