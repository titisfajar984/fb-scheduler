<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\FbAccount;
use App\Models\FbPage;
use App\Services\FacebookService;
use Illuminate\Support\Facades\Auth;

class FacebookAuthController extends Controller
{
    protected $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')
            ->scopes(['pages_manage_posts', 'pages_read_engagement', 'pages_show_list'])
            ->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();

            $fbAccount = FbAccount::updateOrCreate(
                ['facebook_id' => $user->id, 'user_id' => auth()->id()],
                [
                    'name' => $user->name,
                    'access_token' => $user->token,
                    'token_expired_at' => now()->addSeconds($user->expiresIn),
                ]
            );

            $pages = $this->facebookService->getPages($user->token);

            foreach ($pages as $page) {
                FbPage::updateOrCreate(
                    ['page_id' => $page['id'], 'fb_account_id' => $fbAccount->id],
                    [
                        'page_name' => $page['name'],
                        'page_access_token' => $page['access_token'],
                    ]
                );
            }

            return redirect()->route('pages.index')->with('success', 'Facebook account connected successfully!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Error connecting Facebook account: ' . $e->getMessage());
        }
    }
}
