<?php
namespace App\Services;

use App\Models\Invitation;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Mail\GeneralMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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

    public function acceptOnboardRequest(int $id)
    {
        try{
            DB::beginTransaction();
            $invitation = Invitation::with('invitedBy','tenant')->find($id);
            $invitation->accepted_at = date('Y-m-d H:i:s');
            $existingUser = DB::table('users')->where('email', $invitation->email)->exists();
            if($existingUser) {
                abort(403, 'Access denied. Your account is already exist.');
            }
            $invitation->save();
            $role = Role::where('slug', $invitation->role)->first();
            $password = Str::random(8);
            $data = [
                'name' => $invitation->name,
                'email' => $invitation->email,
                'tenant_id' => $invitation->tenant_id,
                'role_id' => $role->id,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => Hash::make($password),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('users')->insert($data);
            //send mail
            Mail::to($invitation->email)->send(
                    new GeneralMail(
                        name:  $invitation->name,
                        url: route('login'),
                        message: "Your account has been created, login id : $invitation->email and  password :  $password",
                        mailSubject: "Welcome Onboard!."
                    )
                );
            DB::commit();
            return redirect(route('login'));

        } catch(\Exception $e) {
            DB::rollBack();
            Log::error([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            return redirect()->route('/')->withErrors('Server Error.');
        }
    }
}
