<?php
namespace App\Services;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Mail\GeneralMail;
use Illuminate\Support\Facades\Mail;

class InvitationService {
    /**
     * Invitation list
     */

    public function invitationList(Request $request)
    {
        $perPage = $request->per_page ?? 10;
        $invitations = Invitation::with([
                                        'tenant:id,name',
                                        'invitedBy:id,name'
                                    ])->paginate($perPage);

    }

    public function storeInvitation(array $data) {
         return DB::transaction(function () use ($data) {
            $authUser = request()->user();
            //Super admin can send invitation to any company admin
            $data['company'] = $data['company'] ?? $authUser->tenant_id;
            //Admin can send invitation to own company member
            $existingInvitation = Invitation::where('tenant_id', $data['company'])
            ->where('email', $data['email'])
            ->where('expired_at', '>', now())
            ->first();

            if ($existingInvitation) {
                throw ValidationException::withMessages([
                    'invited_to' => 'An active invitation already exists.',
                ]);
            }
            $invitation = Invitation::create([
                'tenant_id'  => $data['company'],
                'invited_by' => $authUser->id,
                'name'  => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'expired_at' => now()->addDays(7),
            ]);
            $invitation->load([
                                'tenant:id,name',
                                'invitedBy:id,name'
                            ]);
            $mailData =[
                'name' => $invitation->name,
                'email' => $invitation->email,
                'message' => 'You are invited to subscribe in our'. $invitation->tenant->name .' your role is '. $invitation->role,
            ];
            //send invitation on mail
            Mail::to($data['email'])->send(
                new GeneralMail(
                    name: $mailData['name'],
                    url: route('invitationAccept', $invitation->id),
                    message: $mailData['message'],
                    mailSubject: 'Subscription Invitation.'
                )
            );
            return $invitation;
        });
    }
}
